<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->smallInteger('round');
            $table->foreignId('player_1_id')->nullable()->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('player_2_id')->nullable()->references('id')->on('users')->cascadeOnDelete();

            //0- result not entered yet, id of a winner otherwise
            $table->unsignedTinyInteger('player_1_result');

            //0- result not entered yet, id of a winner otherwise
            $table->unsignedTinyInteger('player_2_result');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_results');
        Schema::dropIfExists('games');
    }
}
