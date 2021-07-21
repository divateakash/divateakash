<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoluumFieldsToTeams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('voluum_api_user')->nullable();
            $table->string('voluum_api_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            //
        });
    }
}
