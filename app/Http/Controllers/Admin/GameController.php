<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ResultTypeEnums;
use App\Http\Controllers\Controller;
use App\Models\Forecast;
use App\Models\Game;
use App\ValueObjects\AdminTable\AdminTable;
use App\ValueObjects\AdminTable\AdminTableCell;
use App\ValueObjects\AdminTable\AdminTableRow;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as FoundationApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View as IlluminateView;
use Mauricius\LaravelHtmx\Http\HtmxRequest;

class GameController extends Controller
{

    protected AdminTable|null $adminTable = null;

    protected Collection $models;

    public function bearer(): ?RedirectResponse
    {
        if (Auth::id() !== 1) {
            return redirect()->to('/');
        }

        return null;
    }

    public function index(): Application|Factory|View|FoundationApplication|IlluminateView|string
    {
        if ($redirect = $this->bearer()) {
            return $redirect;
        }

        return view('admin.table', ['adminTable' => $this->initList()->adminTable]);
    }

    public function edit(string $id): View
    {
        /**
         * @var AdminTableRow $row
         */
        $adminTableRow = collect($this->initList()->adminTable->rows)->where('id', $id)->first();

        return view('admin.table', ['row' => $adminTableRow]);
    }

    public function copy(string $id): View
    {
        $model = Game::query()->where('id', $id)->first();
        $model = $model->replicate();
        $model->save();

        return $this->index();
    }

    public function update(Request $request, string $id): string
    {
        $model = Game::query()->where('id', $id)->first();
        foreach ($request->all() as $key => $value){
            if(in_array($key, ['created_at', 'updated_at', 'deleted_at'])){
                continue;
            }


            $value = $this->ifDateConvertToUTC($value);

            $model->{$key} = $value;
        }
        $model->save();

        return redirect()->route('admin.games.index');
    }

    public function __invoke(
        HtmxRequest $request,
        int $gameId
    ): Application|Factory|View|FoundationApplication|IlluminateView|string {
        if ($request->isHtmxRequest()) {
        }

        $game = Game::query()->where('id', $gameId)->first();
        $result = Forecast::query()->where('user_id', Auth::id())->where('game_id', $gameId)
            ->first()?->result;

        if ($game->date->lt(now())) {
            return '<span>' . ($result ?: '-') . '</span>';
        }


        $updateRoute = route('forecasts.update', $gameId);

        $uniqId = uniqid() . time();
        $uniqId2 = uniqid() . time() . time();

        return <<<HTML
<div id="$uniqId2">
<input type="text" name="result" value="$result" id="$uniqId">
<button hx-put="$updateRoute" hx-include="[id='$uniqId']" hx-target="[id='$uniqId2']" hx-target="[id='$uniqId2']" hx-swap="outerHTML">submit</button>
</div>
HTML;
    }

    protected function initList(): self
    {
        $adminTableRows = [];

        Game::query()->with(['teamHome', 'teamAway'])->orderBy('date', 'desc')->orderBy('id', 'desc')->get()->each(
            function (Game $game) use (&$adminTableRows) {
                $adminTableCells = [];

                foreach ($game->getAttributes() as $column => $value) {
                    if(in_array($column, ['created_at', 'updated_at', 'deleted_at'])){
                        continue;
                    }

                    if ($column === 'team_home_id') {
                        $adminTableCells[] = new AdminTableCell(
                            columnName: $column,
                            value: $value,
                            isSelect: true,
                            options: $game->teamHome->orderBy('code')->pluck('code', 'id')->toArray(),
                        );
                        continue;
                    }

                    if ($column === 'team_away_id') {
                        $adminTableCells[] = new AdminTableCell(
                            columnName: $column,
                            value: $value,
                            isSelect: true,
                            options: $game->teamAway->orderBy('code')->pluck('code', 'id')->toArray(),
                        );
                        continue;
                    }

                    if ($column === 'result_type') {
                        $adminTableCells[] = new AdminTableCell(
                            columnName: $column,
                            value: $value,
                            isSelect: true,
                            options: array_reduce(ResultTypeEnums::cases(), function($result = [], $item){
                                $result[$item->value] = $item->name;
                                return $result;
                            }),
                        );
                        continue;
                    }

                    $adminTableCells[] = new AdminTableCell(
                        columnName: $column,
                        value: $this->ifDateConvertToLocalTimeZone($value),
                        isSelect: false,
                        options: []
                    );
                }

                $adminTableCells[] = new AdminTableCell(
                    columnName: 'action',
                    value: '<div><span class="cursor-pointer" hx-get="' . route(
                        'admin.games.edit',
                        $game->id
                    ) . '" hx-target="#edit-form-place" style="color: blue; cursor: pointer;">Edit</span></div>',
                    isSelect: false,
                    isAction: true,
                    options: []
                );

                $adminTableCells[] = new AdminTableCell(
                    columnName: 'copy',
                    value: '<div><a class="cursor-pointer" href="' . route(
                        'admin.games.copy',
                        $game->id
                    ) . '" style="color: blue; cursor: pointer;">Copy</a></div>',
                    isSelect: false,
                    isAction: true,
                    options: []
                );

                $adminTableRows[] = new AdminTableRow(id: $game->id, cells: $adminTableCells);

                return $game;
            }
        );

        $this->adminTable = new AdminTable(title: 'Games', rows: $adminTableRows);
        return $this;
    }

    protected function ifDateConvertToUTC(string $datetime, string $localTimeZone = 'Europe/Riga'): string
    {
        try {
            $dateFormat = 'Y-m-d H:i:s';
            $carbon = Carbon::createFromFormat($dateFormat, $datetime, $localTimeZone);
            if($carbon->format($dateFormat) == $datetime){
                $datetime = $carbon->setTimezone('UTC')->format($dateFormat);
            }
        } catch (Exception){
        }

        return $datetime;
    }

    protected function ifDateConvertToLocalTimeZone(string $datetime, string $localTimeZone = 'Europe/Riga'): string
    {
        try {
            $dateFormat = 'Y-m-d H:i:s';
            $carbon = Carbon::createFromFormat($dateFormat, $datetime);
            if($carbon->format($dateFormat) == $datetime){
                $datetime = $carbon->setTimezone($localTimeZone)->format($dateFormat);
            }
        } catch (Exception){
        }

        return $datetime;
    }

}
