<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new Carbon();
        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Administrador',
            'email' => 'admin@domain.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'active' => 1,
            'role_id' => 1,
            'settings' => '{}',
            'last_login' => $now,
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
