<?php

namespace App\Services;

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
}
