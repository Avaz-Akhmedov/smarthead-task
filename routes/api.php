<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum', 'role:admin|manager'])->group(function () {
    Route::get('/tickets/statistics', [TicketController::class, 'index']);

    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::patch('/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus']);
});

Route::post('/tickets', [TicketController::class, 'store']);
