<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{

    public function firstOrCreate(array $data)
    {
        return Customer::query()->firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
            ]
        );
    }

}
