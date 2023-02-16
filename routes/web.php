<?php

use Illuminate\Foundation\Application;
use \App\Http\Controllers\BoardsController;
use \App\Http\Controllers\CardListsController;
use \App\Http\Controllers\CardsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('/boards/{board}/{card?}', [BoardsController::class, 'show'])->name('boards.show');
    Route::put('/boards/{board}', [BoardsController::class, 'update'])->name('boards.update');
    Route::get('/boards', [BoardsController::class, 'index'])->name('boards');
    Route::post('/boards', [BoardsController::class, 'store'])->name('boards.store');

    Route::post('/boards/{board}/lists', [CardListsController::class, 'store'])->name('cardLists.store');
    Route::post('/cards', [CardsController::class, 'store'])->name('cards.store');
    Route::put('/cards/{card}', [CardsController::class, 'update'])->name('cards.update');
    Route::put('/cards/{card}/move', [CardsController::class, 'move'])->name('cards.move');
    Route::delete('/cards/{card}', [CardsController::class, 'destroy'])->name('cards.destroy');
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
