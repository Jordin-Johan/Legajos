<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;

Route::redirect('/', '/dashboard/login');

// Ruta nombrada 'login' que Laravel y Filament necesitan
Route::get('/dashboard/login', Login::class)->name('login');
