<?php

use App\Http\Controllers\CategoryController;
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