<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('web')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_zip_code')->nullable();
            $table->string('password')->index();
            $table->string('school')->nullable();
            $table->integer('year_school_termination')->nullable();
            $table->integer('year_school_termination_stu')->nullable();
            $table->string('specialisation')->nullable();
            $table->integer('education_attainment_stu')->nullable();
            $table->string('ais_uid')->nullable();
            $table->integer('group_id')->nullable(false);
            $table->string('description')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
