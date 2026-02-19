<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/clear-cache', function () {
    \Artisan::call('optimize:clear');
    return 'Cache cleared!';
});

Route::get('/test', function (Request $request) {
    return response()->json([
        'message' => 'API is working!',
        'server_time' => now()->toDateTimeString(),
        'environment' => app()->environment(),
    ]);
});