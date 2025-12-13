<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class TicketRepository
{
    public function getFilteredTickets(array $filters)
    {
        return Ticket::query()
            ->with('customer')
            ->filter($filters)
            ->latest()
            ->paginate(16);
    }

    public function updateStatus(Ticket $ticket, string $status, $answeredAt): void
    {
        $ticket->update([
            'status' => $status,
            'answered_at' => $answeredAt
        ]);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist
     */
    public function createTicket(Customer $customer, array $data): Ticket
    {

        /* @var Ticket $ticket */

        $ticket = $customer->tickets()->create([
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        if (!empty($data['attachments'])) {
            foreach ($data['attachments'] as $attachment) {
                $ticket->addMedia($attachment)->toMediaCollection('attachments');
            }
        }

        return $ticket;
    }

    public function hasRecentTicket(?string $email, ?string $phone): bool
    {
        return Ticket::query()
            ->whereHas('customer', function (Builder $query) use ($email, $phone) {
                if ($email) {
                    $query->where('email', $email);
                }
                if ($phone) {
                    $query->orWhere('phone_number', $phone);
                }
            })
            ->where('created_at', '>=', now()->subDay())
            ->exists();

    }
}
