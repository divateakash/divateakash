<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('minimum_amount')->nullable();
            $table->integer('maximum_amount')->nullable();
            $table->integer('maximum_sends')->nullable();
            $table->unsignedBigInteger('domain_provider_id');
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
        Schema::dropIfExists('domain_groups');
    }
}
