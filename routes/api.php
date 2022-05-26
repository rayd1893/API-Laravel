<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FareController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StatusOrderController;
use App\Http\Controllers\AssignationIntentController;

Route::post('/fares', [FareController::class, 'store']);
Route::post('/fares/{uuid}', [FareController::class, 'show']);
Route::post('/fares/{country}/get', [FareController::class, 'fares']);

Route::post('/orders', [OrderController::class, 'store']);

Route::post('/orders/commissions', [StatusOrderController::class, 'handleDelivered']);

Route::put('/intents/{uuid}', [AssignationIntentController::class, 'update']);

Route::get('/healthz', fn () => 'ok');
