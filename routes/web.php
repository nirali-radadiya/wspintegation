<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SendWhatsappMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/send-whatsapp', [SendWhatsappMessageController::class, 'index'])->name('send-whatsapp');
Route::post('/send-whatsapp/store', [SendWhatsappMessageController::class, 'sendWhatsAppMessage'])->name('send-whatsapp.store');
