<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHandsToSituationsToActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hands_to_situations_to_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hand_id');
            $table->integer('action_id');
            $table->integer('situation_id');
            $table->integer('percentage');
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
        Schema::dropIfExists('hands_to_situations_to_actions');
    }
}
