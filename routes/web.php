<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testController;
use App\Http\Controllers\headerController;
use App\Http\Controllers\footerController;
use App\Http\Controllers\AMQPReceiveTesterController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\RoleRegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CreateEventController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\CompanyAuthController;

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

Route::middleware('web')->group(function () {
    Route::get('/', function () {
        return view('user.home');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/home', function () {
            return view('user.home');
        })->name('user.home');
    });

    Route::group(['middleware' => ['auth:company']], function () {
        Route::get('/home', [CompanyController::class, 'index'])->name('home');
    });

    Route::middleware(['web'])->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('/register', [RegisteredUserController::class, 'store']);
        Route::get('/home', function () {
            return view('user.home');
        })->name('user.home');
    });

    Route::middleware(['web', 'auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Header Routes
    Route::get('/home', [headerController::class, 'home']);
    Route::get('/about', [headerController::class, 'about']);
    Route::get('/events', [headerController::class, 'events']);
    Route::get('/planning', [headerController::class, 'planning']);
    Route::get('/contact', [headerController::class, 'contact']);
    Route::get('/registration', [headerController::class, 'registration']);
    Route::post('/logout', function () {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
    
        if (Auth::guard('company')->check()) {
            Auth::guard('company')->logout();
            Session::flush();
        }
    
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/mijnReservaties', [headerController::class, 'mijnReservaties']);


    // Event creation
    Route::get('/show_events', [headerController::class, 'show_events']);
    Route::post('/send-message-to-events', [EventController::class, 'sendMessageToTopic'])->name('sendMessageToTopic_event');
    Route::match(['get', 'post'], '/create_event', [EventController::class, 'test'])->name('test_event');
    Route::post('/create_event', [EventController::class, 'create_event'])->name('create_event');

    // Register to event
    Route::post('/events/register', [EventController::class, 'registerToEvent']);

    // Event details
    Route::get('/event_details/{id}', [EventController::class, 'eventDetails']);

    // Company creation
    Route::get('/make_company', [headerController::class, 'show_company']);
    Route::post('/create_company', [CompanyController::class, 'create_company'])->name('create_company');
    // Route::match(['get', 'post'], '/create_company', [CompanyController::class, 'test'])->name('test_company');
    Route::post('/send-message-to-topics_company', [CompanyController::class, 'sendMessageToTopic'])->name('sendMessageToTopic_company');

    // Role register
    Route::get('/register_speaker', [RoleRegisterController::class, 'register_speaker']);
    Route::get('/register_company', [RoleRegisterController::class, 'register_company']);

    // Footer Routes
    Route::get('/privacy', [footerController::class, 'privacy']);

    // Test display rabbit
    Route::get('/display', [AMQPReceiveTesterController::class, 'displayMessage'])->name('display.message');

    Route::fallback(function () {
        abort(404, 'Page not found');
    });

    Route::post('/events/create', [CreateEventController::class, 'createEvent'])->name('create_event');

    Route::get('/home', function () {
        return view('user.home');
    })->name('user.home');

    require __DIR__ . '/auth.php';
});
