<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannedWordsIndexOnOutbounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbounds', function (Blueprint $table) {
            $table->index(['campaign_id', 'message_id', 'response']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outbounds', function (Blueprint $table) {
            //
        });
    }
}
