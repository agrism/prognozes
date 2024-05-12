<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View as IlluminateView;

class IndexController extends Controller
{
    public function __invoke(Request $request): Application|Factory|View|FoundationApplication|IlluminateView
    {
        $games = Game::query()->with(['teamHome', 'teamAway', 'forecast' =>function($q){
            $q->where('user_id', Auth::id());
        }])->get();

//        dd($games->toArray());

        return view('index', compact('games'));
    }
}
