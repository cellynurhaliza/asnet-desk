<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    if (auth('web')->check()) {
        return redirect()->route('dashboard');
    }

    if (auth('customer')->check()) {
        return redirect()->route('customer.dashboard');
    }

    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.post');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::middleware(['auth:web', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/tickets/export', [TicketController::class, 'export'])
        ->name('tickets.export');

    Route::resource('tickets', TicketController::class);

    Route::patch('/tickets/{ticket}/handling', [TicketController::class, 'updateHandling'])
        ->name('tickets.updateHandling');

    Route::post('/tickets/{ticket}/notes', [TicketController::class, 'addNote'])
        ->name('tickets.addNote');

    Route::get('/tickets/customer-suggestions', [TicketController::class, 'customerSuggestions'])
        ->name('tickets.customerSuggestions');

    Route::resource('categories', CategoryController::class);

    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->name('categories.show');

    Route::resource('customers', CustomerController::class)
        ->except(['show']);

    Route::get('/customers/{customer}', [CustomerController::class, 'show'])
        ->name('customers.show');

    Route::resource('technicians', TechnicianController::class);
});

Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth:customer', 'customer'])
    ->group(function () {

        Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/tickets', [CustomerPortalController::class, 'tickets'])
            ->name('tickets.index');

        Route::get('/tickets/create', [CustomerPortalController::class, 'createTicket'])
            ->name('tickets.create');

        Route::post('/tickets', [CustomerPortalController::class, 'storeTicket'])
            ->name('tickets.store');

        Route::get('/tickets/{ticket}', [CustomerPortalController::class, 'showTicket'])
            ->name('tickets.show');

        Route::post('/tickets/{ticket}/resolution', [
            CustomerPortalController::class,
            'submitResolutionFeedback',
        ])->name('tickets.resolution');

        Route::get('/profile', [CustomerPortalController::class, 'profile'])
            ->name('profile');

        Route::put('/profile', [CustomerPortalController::class, 'updateProfile'])
            ->name('profile.update');

        Route::put('/profile/password', [CustomerPortalController::class, 'updatePassword'])
            ->name('profile.password');
    });
