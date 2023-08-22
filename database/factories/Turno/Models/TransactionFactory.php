<?php

namespace Database\Factories\Turno\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Turno\Models\Transaction;
use Turno\Transaction\Enums\TransactionType;
use Turno\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Turno\Models\Transaction>
 */
class TransactionFactory extends Factory {

    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type_id = $this->faker->numberBetween(1, 2);
        $amount  = $this->faker->numberBetween(1, 10000);

        if ($type_id === TransactionType::PURCHASE->value) {
            $amount = $amount * -1;
        }

        return [
            'status_id' => $this->faker->numberBetween(1, 3),
            'type_id' => $type_id,
            'customer_id' => User::inRandomOrder()->first()->id,
            'amount' => $amount,
            'description' => $this->faker->sentence(3)
        ];
    }
}
