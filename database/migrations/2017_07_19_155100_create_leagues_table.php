<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id")->unsigned();
            $table->smallInteger("game_id")->unsigned();
            $table->integer("format_id")->unsigned();
            $table->integer("deck_id")->unsigned();
            $table->boolean("completed")->default(0);
            $table->smallInteger("league_win")->default(0);
            $table->smallInteger("league_loss")->default(0);
            $table->boolean("reseted")->default(0);
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
        Schema::dropIfExists('leagues');
    }
}
