<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Turno\Models\TransactionType;

class TransactionTypeSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TransactionType::insertOrIgnore(['id' => 1, 'description' => 'Deposit']);
        TransactionType::insertOrIgnore(['id' => 2, 'description' => 'Purchase']);
    }
}
