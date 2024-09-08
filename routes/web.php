<?php

use App\Http\Controllers\WeatherPredictionController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/weather', [WeatherPredictionController::class, 'index'])->name('weather.index'); // Use 'index' instead of 'showForm'
Route::post('/predict', [WeatherPredictionController::class, 'predict'])->name('predict');
