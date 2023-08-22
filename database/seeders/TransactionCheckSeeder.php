<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Turno\Models\Transaction;

class TransactionCheckSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactions = Transaction::where('type_id', \Turno\Transaction\Enums\TransactionType::DEPOSIT->value)->get();

        foreach ($transactions as $transaction) {
            $transaction->check()->create([
                'url' => 'checks/generated_by_seeder.jpg'
            ]);
        }
    }
}
