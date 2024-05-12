<?php

namespace App\Console\Commands;

use App\Enums\ResultTypeEnums;
use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use CountryEnums\Country;
use Illuminate\Console\Command;

class SeedData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->handleTeams();
        $this->handleGames();

    }

    protected function handleTeams(): void
    {
        $this->line('Team handling: start');

        $countries = [
            Country::LV,
            Country::PL,
            Country::CZ,
            Country::SK,
            Country::CA,
            Country::US,
            Country::FI,
            Country::SE,
            Country::CH,
            Country::DK,
            Country::AU,
            Country::DE,
            Country::GB,
            Country::FR,
            Country::KZ,
            Country::NO,
        ];

        foreach ($countries as $country){
            if(!Team::query()->where('code', $country->value)->first()){
                $team = new Team;
                $team->name = $country->label();
                $team->code = $country->value;
                $team->save();
            }
        }

        $this->line('Team handling: end');
    }

    protected function handleGames(): void
    {
        $games = [
            $this->factory(homeTeamCountryCode: 'CH', awayTeamCountryCode: 'NO', date: Carbon::parse('2024-05-10 17:20'), result: '5:2'),
            $this->factory(homeTeamCountryCode: 'SK', awayTeamCountryCode: 'DE', date: Carbon::parse('2024-05-10 17:20'), result: '4:6'),
            $this->factory(homeTeamCountryCode: 'CZ', awayTeamCountryCode: 'FI', date: Carbon::parse('2024-05-10 17:20'), result: '1:0 SO'),
            $this->factory(homeTeamCountryCode: 'SE', awayTeamCountryCode: 'US', date: Carbon::parse('2024-05-10 21:20'), result: '5:2'),

            $this->factory(homeTeamCountryCode: 'GB', awayTeamCountryCode: 'CA', date: Carbon::parse('2024-05-11 13:20'), result: '2:4'),
            $this->factory(homeTeamCountryCode: 'FR', awayTeamCountryCode: 'KZ', date: Carbon::parse('2024-05-11 13:20'), result: '1:3'),
            $this->factory(homeTeamCountryCode: 'AU', awayTeamCountryCode: 'DK', date: Carbon::parse('2024-05-11 17:20'), result: '1:5'),

            $this->factory(homeTeamCountryCode: 'PL', awayTeamCountryCode: 'LV', date: Carbon::parse('2024-05-11 17:20'), result: '4:5 OT'),
            $this->factory(homeTeamCountryCode: 'NO', awayTeamCountryCode: 'CZ', date: Carbon::parse('2024-05-11 21:20'), result: '3:6'),
            $this->factory(homeTeamCountryCode: 'US', awayTeamCountryCode: 'DE', date: Carbon::parse('2024-05-11 21:20'), result: '6:1'),

            $this->factory(homeTeamCountryCode: 'FI', awayTeamCountryCode: 'GB', date: Carbon::parse('2024-05-12 13:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'SK', awayTeamCountryCode: 'KZ', date: Carbon::parse('2024-05-12 13:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'DK', awayTeamCountryCode: 'CA', date: Carbon::parse('2024-05-12 17:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'LV', awayTeamCountryCode: 'FR', date: Carbon::parse('2024-05-12 17:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'AU', awayTeamCountryCode: 'CH', date: Carbon::parse('2024-05-12 21:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'SE', awayTeamCountryCode: 'PL', date: Carbon::parse('2024-05-12 21:20'), result: 'upcoming'),
            $this->factory(homeTeamCountryCode: 'NO', awayTeamCountryCode: 'FI', date: Carbon::parse('2024-05-1317:20'), result: 'upcoming'),
        ];

        foreach ($games as $game){
            $teamHome = Team::query()->where('code', data_get($game, 'teamHome.countryCode'))->first();
            $teamAway = Team::query()->where('code', data_get($game, 'teamAway.countryCode'))->first();

            if(!$dbGame = Game::query()->where('team_home_id', $teamHome->id)->where('team_away_id', $teamAway->id)->first()){
                $dbGame = new Game;
                $dbGame->team_home_id = $teamHome->id;
                $dbGame->team_away_id = $teamAway->id;
            }

            $resultString = data_get($game, 'result');
            $resultParts = explode(' ', $resultString);

            $resultType = match (data_get($resultParts, 1)){
                'OT' =>  ResultTypeEnums::WIN_OVERTIME,
                'SO' =>  ResultTypeEnums::WIN_OVERTIME_SHOTS,
                default => ResultTypeEnums::REGULAR
            };

            $dbGame->result_type = $resultType->value;
            $dbGame->result = data_get($game, 'result');
            $dbGame->date = data_get($game, 'date');
            $dbGame->save();
        }
    }

    protected function factory(string $homeTeamCountryCode, string $awayTeamCountryCode, Carbon $date, string $result): array
    {
        return             [
            'playId' => 1,
            'date' => $date->format('Y-m-d H:i:s'),
            'result' => $result,
            'teamHome' => [
                'id' => 3,
                'countryCode' => $homeTeamCountryCode,
            ],
            'teamAway' => [
                'id' => 4,
                'countryCode' => $awayTeamCountryCode,
            ]
        ];
    }
}
