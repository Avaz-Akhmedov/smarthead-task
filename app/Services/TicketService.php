<?php

namespace App\Services;

use App\Enums\TicketStatusEnum;
use App\Models\Ticket;
use App\Repositories\TicketRepository;

class TicketService
{
    public function __construct(
        private TicketRepository $repository
    )
    {
    }

    public function getTicketStatistics(array $filters)
    {
        return $this->repository->getFilteredTickets($filters);
    }

    public function updateStatus(Ticket $ticket,string $status): void
    {
        $answeredAt = $status === TicketStatusEnum::COMPLETED->value ? now() : null;

        $this->repository->updateStatus($ticket, $status, $answeredAt);

    }
}
