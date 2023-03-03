<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'showloginform'])->name('login');
Route::post('postlogin', [AuthController::class, 'postlogin'])->name('postlogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', [AuthController::class, 'showregisterform'])->name('register');
Route::post('postregister', [AuthController::class, 'postregister'])->name('postregister');


Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User Route
    Route::post('user/import', [UserController::class, 'import'])->name('user.import');
    Route::resource('user', UserController::class);

    //Category Route
    Route::resource('category', CategoryController::class);
    //Department Route
    Route::resource('department', DepartmentController::class);

    //Ticket route
    Route::get('ticket/progress', [TicketController::class, 'progress'])->name('ticket.progress');
    Route::get('ticket/hold', [TicketController::class, 'hold'])->name('ticket.hold');
    Route::get('ticket/solved', [TicketController::class, 'solved'])->name('ticket.solved');
    Route::resource('ticket', TicketController::class);
    Route::put('ticket/closed/{ticket}', [TicketController::class, 'closed'])->name('ticket.closed');
    Route::put('ticket/assign/{ticket}', [TicketController::class, 'assign'])->name('ticket.assign');

    //Office Route
    Route::resource('office', OfficeController::class);
    //Location Route
    Route::resource('location', LocationController::class);
    //Item Route
    Route::resource('item', ItemController::class);

    //Profile User Route
    Route::group(['prefix' => 'profile'], function () {
        Route::get('user', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('user/{user}', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('user/password/update', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    //Report Route
    Route::group(['prefix' => 'reports'], function () {
        Route::get('tickets', [ReportController::class, 'index'])->name('report.index');
        Route::get('tickets/exportPdf', [ReportController::class, 'exportPdf'])->name('report.ticket.exportPdf');
    });
});
