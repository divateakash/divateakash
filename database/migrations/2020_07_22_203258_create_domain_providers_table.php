<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('user');
            $table->string('password');
            $table->string('provider');
            $table->unsignedBigInteger('team_id');
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
        Schema::dropIfExists('domain_providers');
    }
}
