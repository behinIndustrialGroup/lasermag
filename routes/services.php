<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Route::prefix('services')->name('services.')->group(function(){
    Route::get('solar', function(){
        return view('services.solar');
    });
});