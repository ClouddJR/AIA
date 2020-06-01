<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('discipline');
            $table->foreignId('organizer_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamp('time');
            $table->string('location');
            $table->string('lat');
            $table->string('lng');
            $table->unsignedSmallInteger('max_participants');
            $table->timestamp('application_deadline');
            $table->timestamps();
        });

        Schema::create('logos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('uri');
            $table->timestamps();
        });

        Schema::create('participations', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->string('license')->unique();
            $table->string('ranking')->unique();
            $table->timestamps();
            $table->primary(['user_id', 'tournament_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participations');
        Schema::dropIfExists('logos');
        Schema::dropIfExists('tournaments');
    }
}
