<?php

namespace App\Services;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Repositories\CustomerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;


class TicketService
{
    public function __construct(
        private TicketRepository   $ticketRepository,
        private CustomerRepository $customerRepository
    )
    {
    }

    public function getTicketStatistics(array $filters)
    {
        return $this->ticketRepository->getFilteredTickets($filters);
    }

    public function updateStatus(Ticket $ticket, string $status): void
    {
        $answeredAt = $status === TicketStatusEnum::COMPLETED->value ? now() : null;

        $this->ticketRepository->updateStatus($ticket, $status, $answeredAt);
    }


    public function store(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $customer = $this->customerRepository->firstOrCreate($data);

            return $this->ticketRepository->createTicket($customer, $data);
        });
    }

    public function hasRecentTicket(?string $email, ?string $phone): bool
    {
        return $this->ticketRepository->hasRecentTicket($email, $phone);

    }
}
