<?php

namespace Database\Factories;

use App\Enums\TicketStatusEnum;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;


class TicketFactory extends Factory
{

    public function definition(): array
    {
        $status = $this->faker->randomElement(TicketStatusEnum::values());

        return [
            'customer_id' => Customer::factory(),
            'subject' => $this->faker->paragraph(),
            'message' => $this->faker->realTextBetween(100, 500),
            'status' => $status,
            'answered_at' => $status === TicketStatusEnum::COMPLETED->value
                ? $this->faker->dateTimeBetween('-10 days', 'now')
                : null
        ];
    }
}
