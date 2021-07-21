<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('timezone')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('carrier');
            $table->string('type')->nullable();
            $table->integer('start_hour');
            $table->integer('end_hour');
            $table->timestamp('suppressed_at')->nullable();
            $table->unsignedBigInteger('catalog_id')->index();
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedInteger('carrier_id')->index();
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
        Schema::dropIfExists('leads');
    }
}
