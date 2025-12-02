<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum','role:admin|manager'])->group(function () {
    Route::get('/tickets/statistics', [TicketController::class, 'index']);

});
