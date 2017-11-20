<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("user_id");
            $table->integer("format_id");
            $table->integer("deck_id");
            $table->integer("opp_deck_id");
            $table->smallInteger("win")->default(0);
            $table->smallInteger("loss")->default(0);
            $table->smallInteger("match_win")->default(0);
            $table->smallInteger("game_win")->default(0);
            $table->smallInteger("play_pre")->default(0);
            $table->smallInteger("draw_pre")->default(0);
            $table->smallInteger("play_post")->default(0);
            $table->smallInteger("draw_post")->default(0);
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
        Schema::dropIfExists('statics');
    }
}
