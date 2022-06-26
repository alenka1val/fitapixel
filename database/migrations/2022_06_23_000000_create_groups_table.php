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
            $table->timestamp('deleted_at')->nullable();
        });

        DB::table('groups')->insert([
            'id' => 1,
            'name' => "Študent FIIT",
            'need_ldap' => "student",
            'permission' => 'photographer',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 2,
            'name' => "Absolvent FIIT",
            'need_ldap' => "other",
            'permission' => 'photographer',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 3,
            'name' => "Záujemcovia o informatiku",
            'permission' => 'photographer',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 4,
            'name' => "Zamestnanci FIIT STU s AIS",
            'need_ldap' => "ext,staff",
            'permission' => 'photographer',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 5,
            'name' => "Zamestnanci FIIT STU bez AIS",
            'permission' => 'photographer',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 6,
            'name' => "Porodca",
            'permission' => 'jury',
            'created_at' => Carbon\Carbon::now()
        ]);

        DB::table('groups')->insert([
            'id' => 7,
            'name' => "Admin",
            'permission' => 'admin',
            'created_at' => Carbon\Carbon::now()
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
