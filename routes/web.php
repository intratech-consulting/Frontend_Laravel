<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Symfony\Component\Routing\RouteCollection;
use App\Http\Controllers\testController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\headerController;
use App\Http\Controllers\footerController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

Route::post('/send-message', [testController::class, 'sendMessage'])->name('send.message');
Route::match(['get', 'post'], '/test', [testController::class, 'test'])->name('test');



//header

Route::get('/home', [headerController::class, 'home']);
Route::get('/about', [headerController::class, 'about']);
Route::get('/planning', [headerController::class, 'planning']);
Route::get('/contact', [headerController::class, 'contact']);


//footer
Route::get('/privacy', [footerController::class, 'privacy']);


require __DIR__.'/auth.php';
