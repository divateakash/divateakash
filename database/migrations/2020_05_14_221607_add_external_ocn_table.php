<?php

use Illuminate\Database\Migrations\Migration;

class AddExternalOcnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('ocn', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('OCN')->index();
        //     $table->string('CommonName');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('ocn');
    }
}
