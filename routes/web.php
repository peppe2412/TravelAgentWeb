<?php

use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicController::class)->group(function(){
    Route::get('/', 'home')->name('home');
});
