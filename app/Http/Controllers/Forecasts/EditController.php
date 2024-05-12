<?php

namespace App\Http\Controllers\Forecasts;

use App\Http\Controllers\Controller;
use App\Models\Forecast;
use App\Models\Game;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View as IlluminateView;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

class EditController extends Controller
{
    public function __invoke(HtmxRequest $request, int $gameId): Application|Factory|View|FoundationApplication|IlluminateView|String
    {
        if($request->isHtmxRequest()){

        }

        $game = Game::query()->where('id', $gameId)->first();
        $result = Forecast::query()->where('user_id', Auth::id())->where('game_id', $gameId)
            ->first()?->result;

        if($game->date->lt(now())){
            return '<span>'.($result ?: '-').'</span>';
        }


        $updateRoute = route('forecasts.update', $gameId);



        $uniqId = uniqid().time();
        $uniqId2 = uniqid().time().time();

        return <<<HTML
<div id="$uniqId2">
<input type="text" name="result" value="$result" id="$uniqId">
<button hx-put="$updateRoute" hx-include="[id='$uniqId']" hx-target="[id='$uniqId2']" hx-target="[id='$uniqId2']" hx-swap="outerHTML">submit</button>
</div>
HTML;
    }

}
