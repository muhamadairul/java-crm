<?php

namespace Webkul\Installer\Database\Seeders\User;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('users')->delete();

        DB::table('roles')->delete();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('roles')->insert([
            [
                'id'              => 1,
                'name'            => 'Super Admin',
                'description'     => 'Super Administrator Role',
                'permission_type' => 'all',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 2,
                'name'            => 'Company Admin',
                'description'     => 'Company Administrator Role',
                'permission_type' => 'all',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'id'              => 3,
                'name'            => 'Sales User',
                'description'     => 'Sales User Role',
                'permission_type' => 'custom',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}
