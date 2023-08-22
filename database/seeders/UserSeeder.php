<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // insertOrIgnore not run mutators
        \Turno\Models\User::insertOrIgnore([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'balance'  => 0,
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        \Turno\Models\User::factory(2)->create();
    }
}
