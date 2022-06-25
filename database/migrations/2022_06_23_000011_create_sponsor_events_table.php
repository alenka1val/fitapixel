<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSponsorEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sponsor_events', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->nullable(false);
            $table->integer('sponsor_id')->nullable(false);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('event_id')->references('id')->on('events');
            $table->foreign('sponsor_id')->references('id')->on('sponsors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sponsor_events');
    }
}
