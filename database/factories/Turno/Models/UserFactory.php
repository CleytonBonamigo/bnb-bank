<?php

namespace Database\Factories\Turno\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Turno\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Turno\Models\User>
 */
class UserFactory extends Factory {

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'username' => $username = $this->faker->userName,
            'password' => $username,
            'balance' => 0,
            'is_admin' => false
        ];
    }
}
