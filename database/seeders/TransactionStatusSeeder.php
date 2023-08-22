<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Turno\Models\TransactionStatus;

class TransactionStatusSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionStatus::insertOrIgnore(['id' => 1, 'description' => 'Pending', 'created_at' => now(), 'updated_at' => now()]);
        TransactionStatus::insertOrIgnore(['id' => 2, 'description' => 'Approved', 'created_at' => now(), 'updated_at' => now()]);
        TransactionStatus::insertOrIgnore(['id' => 3, 'description' => 'Rejected', 'created_at' => now(), 'updated_at' => now()]);
    }
}
