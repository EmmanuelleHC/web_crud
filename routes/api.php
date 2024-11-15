<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.generatePdf');

    Route::apiResource('invoices', InvoiceController::class);

    Route::get('/clients/select', [ClientController::class, 'select']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

