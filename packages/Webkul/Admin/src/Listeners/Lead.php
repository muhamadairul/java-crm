<?php

namespace Webkul\Admin\Listeners;

use Webkul\Email\Repositories\EmailRepository;

class Lead
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EmailRepository $emailRepository) {}

    /**
     * @param  \Webkul\Lead\Models\Lead  $lead
     * @return void
     */
    public function linkToEmail($lead)
    {
        if (! request('email_id')) {
            return;
        }

        $this->emailRepository->update([
            'lead_id' => $lead->id,
        ], request('email_id'));
    }

    /**
     * @param  \Webkul\Lead\Models\Lead  $lead
     * @return void
     */
    public function notifyUser($lead)
    {
        $assignedUser = $lead->user;
        if (! $assignedUser) {
            return;
        }

        try {
            $assignedUser->notify(new \Webkul\Admin\Notifications\InApp\LeadAssigned($lead));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Notification failed: ' . $e->getMessage());
        }
    }
}
