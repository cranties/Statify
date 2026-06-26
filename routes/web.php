<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', \App\Livewire\Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/servers', \App\Livewire\Servers\Index::class)->middleware(['auth', 'verified'])->name('servers');

Route::middleware('auth')->group(function () {
    Route::get('/servers/{server}/services', \App\Livewire\Services\Index::class)->name('services');
    Route::get('/servers/{server}/stats', \App\Livewire\Servers\Stats::class)->name('servers.stats');
    Route::get('/services/{service}/stats', \App\Livewire\Services\Show::class)->name('services.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notification Templates Configuration
    Route::get('/configuration/telegram', \App\Livewire\Configuration\TelegramSettings::class)->name('config.telegram');
    Route::get('/configuration/email', \App\Livewire\Configuration\EmailSettings::class)->name('config.email');
});

require __DIR__.'/auth.php';
