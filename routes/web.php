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

// Patient Area
use App\Http\Controllers\Patient\DashboardController;
use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\MessagesController;
use App\Http\Controllers\Patient\SettingsController;
use App\Http\Controllers\Doctor\MessagesController as DoctorMessagesController;
use App\Http\Controllers\Doctor\AppointmentsController as DoctorAppointmentsController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Admin\AppointmentsController as AdminAppointmentsController;
use App\Http\Controllers\Admin\MessagesController as AdminMessagesController;
use App\Http\Controllers\Admin\NotificationsController as AdminNotificationsController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
Route::get('/account', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:patient'])
    ->name('patient.dashboard');

// Patient appointments
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/account/appointments', [AppointmentController::class, 'index'])->name('patient.appointments.index');
    Route::get('/account/appointments/create', [AppointmentController::class, 'create'])->name('patient.appointments.create');
    Route::post('/account/appointments', [AppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::get('/account/appointments/{appointment}', [AppointmentController::class, 'show'])->name('patient.appointments.show');
    Route::post('/account/appointments/{appointment}/messages', [AppointmentController::class, 'sendMessage'])->name('patient.appointments.messages.send');

    // Messages
    Route::get('/account/messages', [MessagesController::class, 'index'])->name('patient.messages.index');

    // Settings
    Route::get('/account/settings', [SettingsController::class, 'index'])->name('patient.settings.index');
    Route::post('/account/settings', [SettingsController::class, 'update'])->name('patient.settings.update');
    Route::post('/account/settings/photo', [SettingsController::class, 'uploadPhoto'])->name('patient.settings.photo.upload');
    Route::delete('/account/settings/photo', [SettingsController::class, 'deletePhoto'])->name('patient.settings.photo.delete');
});

// Admin Area
Route::view('/admin', 'admin.dashboard')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('admin.users.index');
    Route::view('/admin/doctors', 'admin.doctors.index')->name('admin.doctors.index');
    Route::get('/admin/appointments', [AdminAppointmentsController::class, 'index'])->name('admin.appointments.index');
    Route::get('/admin/appointments/{appointment}', [AdminAppointmentsController::class, 'show'])->name('admin.appointments.show');
    Route::post('/admin/appointments/{appointment}/status', [AdminAppointmentsController::class, 'updateStatus'])->name('admin.appointments.status');
    Route::post('/admin/appointments/{appointment}/reschedule', [AdminAppointmentsController::class, 'reschedule'])->name('admin.appointments.reschedule');
    Route::post('/admin/appointments/{appointment}/messages', [AdminAppointmentsController::class, 'sendMessage'])->name('admin.appointments.messages.send');
    Route::post('/admin/appointments/{appointment}/history', [AdminAppointmentsController::class, 'addHistory'])->name('admin.appointments.history.add');
    Route::get('/admin/messages', [AdminMessagesController::class, 'index'])->name('admin.messages.index');
    Route::post('/admin/messages/broadcast', [AdminMessagesController::class, 'broadcast'])->name('admin.messages.broadcast');
    Route::get('/admin/notifications', [AdminNotificationsController::class, 'index'])->name('admin.notifications.index');
    Route::post('/admin/notifications/{notification}/read', [AdminNotificationsController::class, 'markRead'])->name('admin.notifications.mark');
    Route::post('/admin/notifications/read-all', [AdminNotificationsController::class, 'markAll'])->name('admin.notifications.mark_all');
    Route::get('/admin/settings', [AdminSettingsController::class, 'index'])->name('admin.settings.index');
    Route::post('/admin/settings/maintenance/enable', [AdminSettingsController::class, 'enableMaintenance'])->name('admin.settings.maintenance.enable');
    Route::post('/admin/settings/maintenance/disable', [AdminSettingsController::class, 'disableMaintenance'])->name('admin.settings.maintenance.disable');
    Route::post('/admin/settings/branding', [AdminSettingsController::class, 'updateBranding'])->name('admin.settings.branding');
});

// Doctor Area
Route::view('/doctor', 'doctor.dashboard')
    ->middleware(['auth', 'role:doctor'])
    ->name('doctor.dashboard');

// Doctor portal
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/messages', [DoctorMessagesController::class, 'index'])->name('doctor.messages.index');
    Route::get('/doctor/appointments/{appointment}', [DoctorAppointmentsController::class, 'show'])
        ->name('doctor.appointments.show');
    Route::post('/doctor/appointments/{appointment}/messages', [DoctorAppointmentsController::class, 'reply'])
        ->name('doctor.appointments.messages.reply');
});

require __DIR__.'/auth.php';
