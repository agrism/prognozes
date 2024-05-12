<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('code')->index();
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('team_home_id')->index();
            $table->integer('team_away_id')->index();
            $table->timestamp('date');
            $table->string('result');
            $table->string('result_type');
            $table->timestamps();
        });

        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->index();
            $table->integer('game_id')->index();
            $table->string('result');
            $table->string('result_type');
            $table->timestamps();
        });

        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->integer('forecast_id')->index();
            $table->string('type');
            $table->integer('points');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awards');
        Schema::dropIfExists('forecasts');
        Schema::dropIfExists('games');
        Schema::dropIfExists('teams');
    }
};
