<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Response;

Route::post('register', [AuthController::class, 'register'])
    ->name('register');
Route::post('login', [AuthController::class, 'login'])
    ->name('login');
Route::post('logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:sanctum');
Route::get('dashboard', DashboardController::class)
    ->name('dashboard')
    ->middleware('auth:sanctum');
Route::get('test', function (){
   return response(json_encode([
       'random' => 123456789
   ]), Response::HTTP_OK);
});
