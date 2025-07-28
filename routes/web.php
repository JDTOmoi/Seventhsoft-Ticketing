<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientTicketController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;

Auth::routes();

Route::get('/email/verify', [VerificationController::class, 'viewEmailVerificationPage'])->name('verification.notice');
Route::post('/email/verify/submit', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user', [UserController::class, 'viewUpdateInfo'])->name('updateUserInfo');
    Route::put('/user', [UserController::class, 'updateInfo'])->name('updateUserInfoPost');
    Route::get('/', [ClientTicketController::class, 'index'])->name('tickets');
    Route::get('/tickets', [ClientTicketController::class, 'index'])->name('tickets');
    Route::get('/tickets/create', [ClientTicketController::class, 'viewCreateTicket'])->name('ticketsCreate');
    Route::post('/tickets/create', [ClientTicketController::class, 'createTicket'])->name('ticketsCreatePost');
    Route::get('/tickets/{t}', [ClientTicketController::class, 'viewChat'])->name('chat');
    Route::post('/tickets/{t}', [ClientTicketController::class, 'addChat'])->name('chatPost');
    Route::put('/tickets/{t}', [ClientTicketController::class, 'closeChat'])->name('closeChat');
});

//Time Zone Setting
Route::post('/set-timezone', function (\Illuminate\Http\Request $request) {
    session(['user_timezone' => $request->timezone]);
    return response()->json(['status' => 'ok']);
});