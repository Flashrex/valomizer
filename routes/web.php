<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('agents');
})->name('home');

/****** ****** ****** ****** ****** Agents ****** ****** ****** ****** ******/
Route::get('agents', [AgentController::class, 'index'])->name('agents');
Route::patch('agents/{agent}', [AgentController::class, 'update'])->name('agent.update')->middleware('auth');
Route::delete('agents/{agent}', [AgentController::class, 'destroy'])->name('agent.destroy')->middleware('auth');

/****** ****** ****** ****** ****** Maps ****** ****** ****** ****** ******/

Route::get('maps', [MapController::class, 'index'])->name('maps');
Route::patch('maps/{map}', [MapController::class, 'update'])->name('map.update')->middleware('auth');
Route::delete('maps/{map}', [MapController::class, 'destroy'])->name('map.destroy')->middleware('auth');

/****** ****** ****** ****** ****** Administration ****** ****** ****** ****** ******/

Route::get('admin', [AdminController::class, 'login'])->name('admin.login');
Route::post('admin/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::get('admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('auth');

Route::post('admin/fetch-agents', [AdminController::class, 'fetchAgents'])->name('admin.fetch.agents')->middleware('auth');
Route::post('admin/fetch-maps', [AdminController::class, 'fetchMaps'])->name('admin.fetch.maps')->middleware('auth');


/****** ****** ****** ****** ****** Other ****** ****** ****** ****** ******/
Route::get('about', function () {
    return Inertia::render('About');
})->name('about');





