<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemeEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_events', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->nullable(false);
            $table->integer('theme_id')->nullable(false);
            $table->timestamps();
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('theme_id')->references('id')->on('themes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_events');
    }
}
