<?php

namespace Tests\Feature;

use App\Enums\UserRoleEnum;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_to_get_all_tickets()
    {
        Customer::factory(30);
        Ticket::factory(60)->create();


        $this
            ->actingAs($this->createRoleAndUser())
            ->getJson('api/tickets/statistics')
            ->assertOk()
            ->assertJsonCount(16, 'data');

    }


    public function test_to_see_each_ticket()
    {
        Customer::factory()->create();
        $ticket = Ticket::factory()->create();

        $this->actingAs($this->createRoleAndUser())
            ->getJson('api/tickets/2323232')
            ->assertNotFound();

        $this->actingAs($this->createRoleAndUser())
            ->getJson("api/tickets/$ticket->id")
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'subject',
                    'message',
                    'status',
                    'customer',
                    'attachments',
                    'answered_at',
                    'created_at'
                ]
            ]);
    }

    public function test_to_create_new_ticket()
    {

        $data = [
            'subject' => 'Test subject',
            'message' => 'Test message',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '+123456789',
        ];

        $invalidData = [];

        $this->postJson('api/tickets', $invalidData)->assertUnprocessable();


        $response = $this->postJson('api/tickets', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'success'
            ]);

        $this->assertDatabaseHas('customers', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test subject',
            'message' => 'Test message',
        ]);
    }



    public function test_to_see_if_it_allows_ticket_if_more_than_24_hours_passed()
    {
        $user = $this->createRoleAndUser();

        $customer = Customer::factory()->create([
            'email' => 'john@example.com',
            'phone_number' => '+123456789',
        ]);

        Ticket::factory()->create([
            'customer_id' => $customer->id,
            'created_at' => now()->subDays(2),
        ]);

        $data = [
            'subject' => 'New subject',
            'message' => 'New message',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '+123456789',
        ];

        $response = $this->actingAs($user)
            ->postJson('api/tickets', $data);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);
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
