<?php

namespace App\Repositories;

use App\Models\Ticket;

class TicketRepository
{
    public function getFilteredTickets(array $filters)
    {
        return Ticket::query()
            ->with('customer')
            ->filter($filters)
            ->latest()
            ->paginate();
    }

    public function updateStatus(Ticket $ticket, string $status, $answeredAt): void
    {
        $ticket->update([
            'status' => $status,
            'answered_at' => $answeredAt
        ]);
    }
}
