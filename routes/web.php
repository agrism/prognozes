<?php

use App\Http\Controllers\Admin\GameController;
use App\Http\Controllers\Forecasts\EditController as ForecastEditController;
use App\Http\Controllers\Forecasts\UpdateController as ForecastUpdateController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminMiddleware;
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


Route::group(['middleware' => AdminMiddleware::class], function(){
    Route::get('/admin/games', [GameController::class, 'index'])->name('admin.games.index');
    Route::get('/admin/games/{id}/edit', [GameController::class, 'edit'])->name('admin.games.edit');
    Route::get('/admin/games/{id}/copy', [GameController::class, 'copy'])->name('admin.games.copy');
    Route::post('/admin/games/{id}', [GameController::class, 'store'])->name('admin.games.store');
    Route::put('/admin/games/{id}', [GameController::class, 'update'])->name('admin.games.update');
});



//Route::get('/email', function(){
//    Mail::raw('Hello world', function (Message $message) {
//        $message->to('7924@inbox.lv');//->from('me@gmail.com');
//    });
//});
