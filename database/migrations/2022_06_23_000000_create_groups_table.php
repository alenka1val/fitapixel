<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->integer('id')->unique();
            $table->string('name')->nullable(false);
            $table->string('need_ldap')->default("");
            $table->enum('permission', ['admin', 'jury', 'photographer'])->nullable(false);
            $table->timestamps();
        });

        DB::table('groups')->insert([
            'id' => 1,
            'name' => "Študent FIIT",
            'need_ldap' => "student",
            'permission' => 'photographer'
        ]);

        DB::table('groups')->insert([
            'id' => 2,
            'name' => "Absolvent FIIT",
            'need_ldap' => "other",
            'permission' => 'photographer'
        ]);

        DB::table('groups')->insert([
            'id' => 3,
            'name' => "Záujemcovia o informatiku",
            'permission' => 'photographer'
        ]);

        DB::table('groups')->insert([
            'id' => 4,
            'name' => "Zamestnanci FIIT STU s AIS",
            'need_ldap' => "ext,staff",
            'permission' => 'photographer'
        ]);

        DB::table('groups')->insert([
            'id' => 5,
            'name' => "Zamestnanci FIIT STU bez AIS",
            'permission' => 'photographer'
        ]);

        DB::table('groups')->insert([
            'id' => 6,
            'name' => "Porodca",
            'permission' => 'jury'
        ]);

        DB::table('groups')->insert([
            'id' => 7,
            'name' => "Admin",
            'permission' => 'admin'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
