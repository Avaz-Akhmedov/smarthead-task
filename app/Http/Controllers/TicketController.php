<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\TicketStatisticRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Http\Resources\ShowTicketResource;
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

    public function show(Ticket $ticket): ShowTicketResource
    {
        return ShowTicketResource::make(
            $ticket->load(['customer'])
        );
    }


    public function store(StoreTicketRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($this->ticketService->hasRecentTicket($data['email'],$data['phone_number'])) {
            return response()->json([
                'success' => false,
                'message' => 'You can only submit one ticket every 24 hour.'
            ], 422);
        }

        $ticket = $this->ticketService->store($data);

        return response()->json([
            'id' => $ticket->id,
            'success' => true
        ], 201);
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
