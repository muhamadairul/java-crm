<?php

use Illuminate\Support\Str;
use Webkul\Core\Models\Company;
use Webkul\Core\Models\Plan;
use Webkul\User\Models\Role;
use Webkul\User\Models\User;
use Webkul\WebForm\Models\WebForm;
use Webkul\Automation\Models\Workflow;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\Pipeline;
use Webkul\Lead\Models\Stage;
use Webkul\Lead\Models\Source;
use Webkul\Lead\Models\Type;
use Webkul\Contact\Models\Person;

uses(\Illuminate\Foundation\Testing\DatabaseTransactions::class);

it('captures web form submissions under the correct tenant company_id', function () {
    // 1. Create a Plan
    $plan = Plan::where('code', 'free-trial-webform')->first();
    if (!$plan) {
        $plan = Plan::create([
            'name'          => 'Free Trial Plan',
            'code'          => 'free-trial-webform',
            'price'         => 0.00,
            'billing_cycle' => 'monthly',
            'max_users'     => 10,
            'max_leads'     => 100,
            'is_active'     => true,
        ]);
    }

    // 2. Create Company
    $company = Company::create([
        'name'      => 'Acme Corp',
        'slug'      => 'acme-corp-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 3. Create Role for Company
    $role = Role::create([
        'name'            => 'Company Admin',
        'permission_type' => 'all',
        'company_id'      => $company->id,
    ]);

    // 4. Create User for Company
    $user = User::create([
        'name'       => 'Company Administrator',
        'email'      => 'admin.acme.' . Str::random(5) . '@acme.com',
        'password'   => bcrypt('password123'),
        'status'     => 1,
        'role_id'    => $role->id,
        'company_id' => $company->id,
    ]);

    // Authenticate the user temporarily so that BelongsToCompany trait sets company_id automatically
    auth()->guard('user')->setUser($user);

    // 5. Create default structures for company (Pipeline, Stage, Source, Type)
    $pipeline = Pipeline::create([
        'name'       => 'Default Sales Pipeline',
        'is_default' => true,
    ]);

    $stage = Stage::create([
        'name'             => 'New Lead',
        'code'             => 'new',
        'lead_pipeline_id' => $pipeline->id,
    ]);

    $source = Source::create([
        'name'       => 'Web Form',
    ]);

    $type = Type::create([
        'name'       => 'Web',
    ]);

    // 6. Create Web Form owned by Company
    $webForm = WebForm::create([
        'form_id'                => (string) Str::uuid(),
        'title'                  => 'Contact Us Web Form',
        'description'            => 'Register leads from landing page',
        'submit_button_label'    => 'Submit Form',
        'submit_success_action'  => 'message',
        'submit_success_content' => 'Thank you for submitting!',
        'create_lead'            => true,
    ]);
    
    // Log out to simulate public anonymous web form submission
    auth()->guard('user')->logout();

    // 7. Make public post request simulating Web Form submission
    $response = test()->postJson(route('admin.settings.web_forms.form_store', $webForm->id), [
        'persons' => [
            'name'            => 'John Doe Web',
            'emails'          => [
                ['value' => 'john.web@example.com', 'label' => 'work']
            ],
            'contact_numbers' => [
                ['value' => '0812345678', 'label' => 'work']
            ]
        ],
        'leads' => [
            'title'          => 'Interested in Pro Plan',
            'lead_value'     => 500000,
            'lead_source_id' => $source->id,
            'lead_type_id'   => $type->id,
        ]
    ]);

    $response->assertStatus(200)
             ->assertJsonPath('message', 'Thank you for submitting!');

    // 8. Verify data is captured under correct company_id
    // Verify Person
    $person = Person::withoutGlobalScopes()->where('name', 'John Doe Web')->first();
    test()->assertNotNull($person);
    test()->assertEquals($company->id, $person->company_id);

    // Verify Lead
    $lead = Lead::withoutGlobalScopes()->where('title', 'Interested in Pro Plan')->first();
    test()->assertNotNull($lead);
    test()->assertEquals($company->id, $lead->company_id);
    test()->assertEquals($person->id, $lead->person_id);
    test()->assertEquals($pipeline->id, $lead->lead_pipeline_id);
    test()->assertEquals($stage->id, $lead->lead_pipeline_stage_id);
});

it('triggers workflow automations when a lead is created', function () {
    // 1. Create a Plan and Company
    $plan = Plan::where('code', 'pro-automation')->first();
    if (!$plan) {
        $plan = Plan::create([
            'name'          => 'Pro Automation Plan',
            'code'          => 'pro-automation',
            'price'         => 10.00,
            'billing_cycle' => 'monthly',
            'max_users'     => 10,
            'max_leads'     => 100,
            'is_active'     => true,
        ]);
    }

    $company = Company::create([
        'name'      => 'Beta Corp',
        'slug'      => 'beta-corp-' . Str::random(5),
        'plan_id'   => $plan->id,
        'is_active' => true,
    ]);

    // 2. Create User/Role
    $role = Role::create([
        'name'            => 'Company Admin',
        'permission_type' => 'all',
        'company_id'      => $company->id,
    ]);

    $user = User::create([
        'name'       => 'Beta Admin',
        'email'      => 'admin.beta.' . Str::random(5) . '@beta.com',
        'password'   => bcrypt('password123'),
        'status'     => 1,
        'role_id'    => $role->id,
        'company_id' => $company->id,
    ]);

    // 3. Set authenticated user context
    auth()->guard('user')->setUser($user);

    // 4. Create default structures for company (Pipeline, Stage, Source, Type)
    $pipeline = Pipeline::create([
        'name'       => 'Beta Pipeline',
        'is_default' => true,
        'company_id' => $company->id,
    ]);

    $stage = Stage::create([
        'name'             => 'New Stage',
        'code'             => 'new',
        'lead_pipeline_id' => $pipeline->id,
        'company_id'       => $company->id,
    ]);

    $source = Source::create([
        'name'       => 'Manual Entry',
        'company_id' => $company->id,
    ]);

    $type = Type::create([
        'name'       => 'Inbound',
        'company_id' => $company->id,
    ]);

    // 5. Create Workflow for lead creation event
    // When a lead is created, update its description automatically
    $workflow = Workflow::create([
        'name'           => 'Auto Description Workflow',
        'description'    => 'Automatically populate lead description',
        'entity_type'    => 'leads',
        'event'          => 'lead.create.after',
        'condition_type' => 'and',
        'conditions'     => [],
        'actions'        => [
            [
                'id'        => 'update_lead',
                'attribute' => 'description',
                'value'     => 'Populated automatically by workflow automation',
            ]
        ]
    ]);
    $workflow->company_id = $company->id;
    $workflow->save();

    // 6. Create Lead
    $lead = Lead::create([
        'title'                  => 'Test Workflow Lead',
        'lead_value'             => 1000,
        'user_id'                => $user->id,
        'lead_source_id'         => $source->id,
        'lead_type_id'           => $type->id,
        'lead_pipeline_id'       => $pipeline->id,
        'lead_pipeline_stage_id' => $stage->id,
    ]);

    // Manually dispatch event to trigger workflow
    event('lead.create.after', $lead);

    // 7. Verify the workflow ran and updated the lead description
    $lead->refresh();
    test()->assertEquals('Populated automatically by workflow automation', $lead->description);
});
