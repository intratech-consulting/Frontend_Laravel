<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Symfony\Component\Routing\RouteCollection;
use App\Http\Controllers\testController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\headerController;
use App\Http\Controllers\footerController;
use App\Http\Controllers\AMQPReceiveTesterController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('user.home');
});

Route::post('/send-message-to-topic', [testController::class, 'sendMessageToTopic'])->name('send_message_to_topic');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::match(['get', 'post'], '/test', [testController::class, 'test'])->name('test');



//header

Route::get('/home', [headerController::class, 'home']);
Route::get('/about', [headerController::class, 'about']);
Route::get('/events', [headerController::class, 'events']);
Route::get('/planning', [headerController::class, 'planning']);
Route::get('/contact', [headerController::class, 'contact']);


//footer
Route::get('/privacy', [footerController::class, 'privacy']);


//test display rabbit
Route::get('/display', [AMQPReceiveTesterController::class, 'displayMessage'])->name('display.message');

Route::fallback(function () {
    abort(404, 'Page not found');
});

Route::post('/test', [testController::class, 'register'])->name('register_test');

require __DIR__.'/auth.php';
