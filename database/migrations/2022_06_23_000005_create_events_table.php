<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name')->nullable(false);
            $table->string('url_path')->nullable(false);
            $table->date('started_at')->useCurrent();
            $table->date('finished_at')->useCurrent();
            $table->date('voted_at')->useCurrent();
            $table->date('voted_to')->useCurrent();
            $table->string('image_folder')->nullable(false);
            $table->integer('min_width')->default(720);
            $table->integer('max_width')->default(1920);
            $table->integer('min_height')->default(480);
            $table->integer('max_height')->default(1080);
            $table->string('allowed_ratios')->default("3x2");
            $table->text('description')->nullable(false);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
