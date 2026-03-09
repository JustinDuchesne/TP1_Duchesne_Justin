<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/equipment', [EquipmentController::class,'index']);
Route::get('/equipment/{id}', [EquipmentController::class,'show']);

Route::get('/equipment/popularity/{id}', [EquipmentController::class,'popularity']);
Route::get('/equipment/average/{id}', [EquipmentController::class,'average']);

Route::delete('/review/{id}', [ReviewController::class,'destroy']); 

Route::post('/user', [UserController::class,'store']);
Route::patch('/user/{id}', [UserController::class,'update']);
