<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Installer\Database\Seeders\DatabaseSeeder as JavaCrmDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $companySeeder = new CompanySeeder();
        $result = $companySeeder->run();

        $this->call(JavaCrmDatabaseSeeder::class, false, ['parameters' => $result]);
    }
}
