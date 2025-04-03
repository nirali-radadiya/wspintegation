<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\SendWhatsappMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
 return redirect()->route('register');
});

Auth::routes();
Route::get('/register', [IndexController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [IndexController::class, 'register'])->name('register');

Route::get('/verify-otp', [IndexController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [IndexController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/resend-otp', [IndexController::class, 'resendOtp'])->name('otp.resend');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/send-whatsapp', [SendWhatsappMessageController::class, 'showForm'])->name('send-whatsapp.form');
    Route::post('/send-whatsapp/store', [SendWhatsappMessageController::class, 'sendWhatsAppMessage'])->name('send-whatsapp.store');
    Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
    Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');
});
