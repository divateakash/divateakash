<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->unsignedBigInteger('message_id')->nullable()->index();
            $table->unsignedBigInteger('number_id')->nullable()->index();
            $table->unsignedBigInteger('domain_id')->nullable()->index();

            $table->index(['campaign_id', 'bot']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clicks', function (Blueprint $table) {
            //
        });
    }
}
