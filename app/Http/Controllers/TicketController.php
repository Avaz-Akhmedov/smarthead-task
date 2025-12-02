<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketStatisticRequest;
use App\Http\Resources\TicketStatisticResource;
use App\Services\TicketService;
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
}
