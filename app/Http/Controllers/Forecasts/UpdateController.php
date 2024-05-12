<?php

namespace App\Http\Controllers\Forecasts;

use App\Enums\ResultTypeEnums;
use App\Http\Controllers\Controller;
use App\Models\Forecast;
use App\Models\Game;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View as IlluminateView;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

class UpdateController extends Controller
{
    protected int $gameId;
    protected string $result;
    public function __invoke(HtmxRequest $request, int $gameId): Application|Factory|View|FoundationApplication|IlluminateView|string
    {
        if($request->isHtmxRequest()){

        }



        $this->gameId = $gameId;
        $this->result = $request->get('result');

        if($fail = $this->validateResult()){
            return $fail;
        }

        $forecast = Forecast::query()->where('user_id', Auth::id())->where('game_id', $this->gameId)->first();

        if(!$forecast){
            $forecast = new Forecast;
            $forecast->user_id = Auth::id();
            $forecast->game_id = $this->gameId;
        }

        $forecast->result_type = ResultTypeEnums::REGULAR->value;
        $forecast->result = $this->result;
        $forecast->save();

        $editRoute = route('forecasts.edit', $gameId);

        $uniqId = uniqid().time();

        return <<<HTML
<div id="$uniqId">$this->result
   <a hx-get="$editRoute"
   hx-swap="outerHTML"
   hx-target="[id='$uniqId']"
   class="font-medium text-blue-600 hover:text-blue-800 cursor-pointer">Edit</a>
</div>
HTML;
    }

    protected function validateResult(): ?string
    {
        if(!str_contains($this->result, ':')){
            return $this->errorResponse('Missing `:` in input');
        }

        $items = explode(':', $this->result);

        $teamHomePoints = data_get($items, 0, 'x');
        $teamAwayPoints = data_get($items, 1, 'x');

        if($teamHomePoints === 'x'){
            return $this->errorResponse('Home team points incorrect');
        }
        if($teamAwayPoints === 'x'){
            return $this->errorResponse('Away team points incorrect');
        }

        if(Str::of(intval($teamHomePoints))->value() !== Str::of($teamHomePoints)->value()){
            return $this->errorResponse('Home team points not number');
        }

        if(Str::of(intval($teamAwayPoints))->value() !== Str::of($teamAwayPoints)->value()){
            return $this->errorResponse('Away team points not number');
        }

        return null;
    }

    protected function errorResponse(string $error): string
    {
        $updateRoute = route('forecasts.update', $this->gameId);

        $uniqId = uniqid().time();
        $uniqId2 = uniqid().time().time();

        return <<<HTML
<div id="$uniqId2">
<span class="text-red-600 text text-sm">$error</span>
<input type="text" name="result" value="$this->result" id="$uniqId">
<button hx-put="$updateRoute" hx-include="[id='$uniqId']" hx-target="[id='$uniqId2']">submit</button>
</div>
HTML;
    }
}
