<?php

use Illuminate\Database\Migrations\Migration;

class AddExternalPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('phones', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('NPA')->index();
        //     $table->string('NXX')->index();
        //     $table->string('BLOCK_ID')->index();
        //     $table->string('LTYPE');
        //     $table->string('STATE');
        //     $table->string('OCN');
        //     $table->string('OLSON');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('phones');
    }
}
