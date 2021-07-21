<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSendTimeIndexToOutbounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbounds', function (Blueprint $table) {
            $table->index(['account_id', 'processed', 'send_at', 'reply_id', 'campaign_id'], 'outbounds_process_account');
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
