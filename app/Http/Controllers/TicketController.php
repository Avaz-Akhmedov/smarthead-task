<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStatisticRequest;
use App\Http\Resources\TicketStatisticResource;
use App\Models\Ticket;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketController extends Controller
{
    public function index(TicketStatisticRequest $request) : ResourceCollection
    {
        $tickets = Ticket::query()
            ->with('customer')
            ->filter($request->validated())
            ->latest()
            ->paginate();

        return TicketStatisticResource::collection($tickets);
    }
}
