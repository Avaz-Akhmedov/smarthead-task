<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStatisticRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\TicketStatisticResource;
use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService
    )
    {
    }

    public function index(TicketStatisticRequest $request): ResourceCollection
    {
        return TicketStatisticResource::collection(
            $this->ticketService->getTicketStatistics($request->validated())
        );
    }

    public function show(Ticket $ticket): TicketStatisticResource
    {
        return TicketStatisticResource::make(
            $ticket->load(['customer', 'files'])
        );
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): JsonResponse
    {

        $this->ticketService->updateStatus(
            $ticket,
            $request->validated()['status']
        );
        return response()->json([
            'success' => true
        ]);
    }
}
