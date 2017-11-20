<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
          $table->boolean("net_type")->default(1);
          $table->boolean("top_8")->default(0);
          $table->integer("tournament_id")->nullable();
          $table->tinyInteger("type")->unsigned()->default(1);
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
          $table->dropColumn("net_type");
          $table->dropColumn("top_8");
          $table->dropColumn("type");
          $table->dropColumn("tournament_id");
        });
    }
}
