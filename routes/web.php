<?php

use App\Http\Controllers\WeatherPredictionController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/weather', [WeatherPredictionController::class, 'index'])->name('weather.index'); // Use 'index' instead of 'showForm'
Route::post('/predict', [WeatherPredictionController::class, 'predict'])->name('predict');


Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return "Database connected successfully: " . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return "Database connection failed: " . $e->getMessage();
    }
});