<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
          $table->increments('id');
          $table->integer("league_id");
          $table->string("opponent_name");
          $table->integer("format_id");
          $table->integer("deck_id");
          $table->integer("opp_deck_id");
          $table->string("g1_play_draw",8)->nullable();
          $table->smallInteger("g1_start_size")->nullable();
          $table->smallInteger("g1_opp_size")->nullable();
          $table->string("g1_result",8)->nullable();
          $table->string("g2_play_draw",8)->nullable();
          $table->smallInteger("g2_start_size")->nullable();
          $table->smallInteger("g2_opp_size")->nullable();
          $table->string("g2_result",8)->nullable();
          $table->string("g3_play_draw",8)->nullable();
          $table->smallInteger("g3_start_size")->nullable();
          $table->smallInteger("g3_opp_size")->nullable();
          $table->string("g3_result",8)->nullable();
          $table->boolean("final_result");
           $table->smallInteger("match_win")->default(0);
           $table->smallInteger("match_loss")->default(0);
          $table->string("key_card")->nullable();
          $table->string("duds")->nullable();
          $table->text("note")->nullable();
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
        Schema::dropIfExists('matches');
    }
}
