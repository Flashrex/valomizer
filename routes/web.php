<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('agents');
})->name('home');

Route::get('agents', [AgentController::class, 'index'])->name('agents');
Route::get('maps', [MapController::class, 'index'])->name('maps');

Route::get('about', function () {
    return Inertia::render('About');
})->name('about');


Route::get('admin', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');
