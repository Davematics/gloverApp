<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ChangeRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'requester_id' => 1,
            'user_id' => 1,
            'request_type' => 'create_new_user',
            'information_to_be_updated' => [
                'first_name' => $this->faker->name(),
                'last_name' => $this->faker->name(),
                'role_id' => 2,
                'email' => $this->faker->unique()->safeEmail(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ],
        ];
    }
}
