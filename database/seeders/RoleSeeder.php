<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use LarAPI\Models\Auth\Role;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $now = new Carbon();
        DB::table('roles')->insert([
            [
                'name'       => Role::ROLE_ADMIN_LABEL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => Role::ROLE_MANAGER_LABEL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => Role::ROLE_NORMAL_LABEL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => Role::ROLE_VIEWER_LABEL,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
