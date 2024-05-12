<?php

use App\Http\Controllers\Forecasts\EditController as ForecastEditController;
use App\Http\Controllers\Forecasts\UpdateController as ForecastUpdateController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    if(!auth()->user()){
//        return redirect()->route('login');
//    }
//
//    return view('index');
//});


Route::group(['middleware' => 'auth:web'], function (){
    Route::get('/', IndexController::class)->name('index');
    Route::get('/profile', ProfileController::class)->name('profile');

    Route::get('/forecasts/{gameId}/edit', ForecastEditController::class)->name('forecasts.edit');
    Route::put('/forecasts/{gameId}', ForecastUpdateController::class)->name('forecasts.update');

});



//Route::get('/email', function(){
//    Mail::raw('Hello world', function (Message $message) {
//        $message->to('7924@inbox.lv');//->from('me@gmail.com');
//    });
//});
