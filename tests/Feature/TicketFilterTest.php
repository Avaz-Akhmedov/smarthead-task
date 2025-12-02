<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketFilterTest extends TestCase
{
    use RefreshDatabase;




    public function test_to_filter_tickets_by_status()
    {
        $customer = Customer::factory()->create();
        Ticket::factory()->create(['status' => 'new', 'customer_id' => $customer->id]);
        Ticket::factory()->create(['status' => 'done', 'customer_id' => $customer->id]);

        $this->actingAs($this->createRoleAndUser())
            ->getJson('api/tickets/statistics?status=new')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['status' => 'new']);
    }


    public function test_to_filter_tickets_by_email()
    {
        $customer1 = Customer::factory()->create(['email' => 'john@example.com']);
        $customer2 = Customer::factory()->create(['email' => 'jane@example.com']);

        Ticket::factory()->create(['customer_id' => $customer1->id]);
        Ticket::factory()->create(['customer_id' => $customer2->id]);

        $this->actingAs($this->createRoleAndUser())
            ->getJson('api/tickets/statistics?email=john')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_to_filter_tickets_by_phone_number()
    {
        $customer1 = Customer::factory()->create(['phone_number' => '+123456789']);
        $customer2 = Customer::factory()->create(['phone_number' => '+987654321']);

        Ticket::factory()->create(['customer_id' => $customer1->id]);
        Ticket::factory()->create(['customer_id' => $customer2->id]);

        $this->actingAs($this->createRoleAndUser())
            ->getJson('api/tickets/statistics?phone_number=+123456789')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }


    public function test_to_filter_tickets_by_date_range()
    {
        $customer = Customer::factory()->create();

        Ticket::factory()->create(['created_at' => now()->subDays(3), 'customer_id' => $customer->id]);
        Ticket::factory()->create(['created_at' => now()->subDays(1), 'customer_id' => $customer->id]);

        $from = now()->subDays(2)->toDateString();
        $to = now()->toDateString();

        $this->actingAs($this->createRoleAndUser())
            ->getJson("api/tickets/statistics?date_from={$from}&date_to={$to}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    private function createRoleAndUser()
    {
        Role::query()->firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value
        ], [
            'guard_name' => 'web'
        ]);

        Role::query()->firstOrCreate([
            'name' => UserRoleEnum::MANAGER->value
        ], [
            'guard_name' => 'web'
        ]);

        $manager = User::factory()->create();

        return $manager->assignRole(UserRoleEnum::MANAGER->value);
    }
}
