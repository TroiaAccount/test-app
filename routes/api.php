<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('/telegram/webhook', [\App\Http\Controllers\TelegramController::class, 'webhook']);
Route::any('/trello/webhook', [\App\Http\Controllers\TrelloController::class, 'webhook']);
