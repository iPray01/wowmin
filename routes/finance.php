<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ExpenseController;

// Donation Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('donations')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('donations.index');
        Route::get('/create', [DonationController::class, 'create'])->name('donations.create');
        Route::post('/', [DonationController::class, 'store'])->name('donations.store');
        Route::get('/{donation}', [DonationController::class, 'show'])->name('donations.show');
        Route::post('/{donation}/process-payment', [DonationController::class, 'processPayment'])
            ->name('donations.process-payment');
        Route::post('/{donation}/gift-aid', [DonationController::class, 'processGiftAid'])
            ->name('donations.process-gift-aid');
        Route::get('/statistics', [DonationController::class, 'getStatistics'])
            ->name('donations.statistics');
    });

    // Payment Webhook (No CSRF)
    Route::post('webhook/stripe', [DonationController::class, 'handlePaymentWebhook'])
        ->name('webhook.stripe')
        ->withoutMiddleware(['csrf']);

    // Campaign Routes
    Route::prefix('campaigns')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/create', [CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::get('/{campaign}/progress', [CampaignController::class, 'progress'])
            ->name('campaigns.progress');
    });

    // Expense Routes
    Route::prefix('expenses')->group(function () {
        Route::get('/', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::get('/create', [ExpenseController::class, 'create'])->name('expenses.create');
        Route::post('/', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::get('/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
        Route::get('/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
        Route::put('/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        Route::get('/statistics', [ExpenseController::class, 'statistics'])
            ->name('expenses.statistics');
        Route::get('/budget-report', [ExpenseController::class, 'budgetReport'])
            ->name('expenses.budget-report');
    });
}); 