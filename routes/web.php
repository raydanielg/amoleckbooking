<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\ContactController;

Route::view('/', 'landing');

Route::view('/doctors', 'doctors.index')->name('doctors.index');
Route::view('/doctors/{id}', 'doctors.show')->name('doctors.show');
Route::view('/testimonials', 'testimonials.index')->name('testimonials.index');
Route::post('/testimonials', [TestimonialController::class, 'store'])
    ->middleware(['auth'])
    ->name('testimonials.store');

// Contact
Route::view('/contact', 'contact.index')->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
