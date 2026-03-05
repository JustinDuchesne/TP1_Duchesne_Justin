<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/categories', [CategoryController::class,'index']);
Route::post('/categories/create', [CategoryController::class,'store']);
Route::get('/categories/{id}', [CategoryController::class,'show']);
Route::post('/categories/update/{id}', [CategoryController::class,'update']);
Route::delete('/categories/delete/{id}', [CategoryController::class,'destroy']); 

Route::get('/equipment', [EquipmentController::class,'index']);
Route::post('/equipment/create', [EquipmentController::class,'store']);
Route::get('/equipment/{id}', [EquipmentController::class,'show']);
Route::patch('/equipment/update/{id}', [EquipmentController::class,'update']); //recheck restfull
Route::delete('/equipment/delete/{id}', [EquipmentController::class,'destroy']); 
Route::get('/equipment/popularity/{id}', [EquipmentController::class,'popularity']); //popularity --Prob érreur a rechecker comment écrire

/*
Route::get('/films', [FilmController::class,'index']);
Route::get('/films/{id}', [FilmController::class,'show']);

Route::get('/languages', [LanguageController::class, 'index']);
Route::get('/languages/{id}', [LanguageController::class, 'show']);

Route::get('/languages/{id}/films', [LanguageFilmController::class, 'index']); //avg ici j'imagine
Route::get('/languages/{id}/films/average', [LanguageFilmController::class, 'average']);

Route::post('/films/create', [FilmController::class,'store']); //a voir si l'on doit changer l'url
Route::delete('/films/delete/{id}', [FilmController::class,'delete']); 
*/