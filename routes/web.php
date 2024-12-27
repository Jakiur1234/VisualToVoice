<?php

use App\Controllers\HomeController;

Route::get('/', [ HomeController::class, 'index' ] );
Route::post('/', [ HomeController::class, 'process' ] );
