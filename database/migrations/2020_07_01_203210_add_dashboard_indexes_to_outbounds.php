<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDashboardIndexesToOutbounds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outbounds', function (Blueprint $table) {
            $table->index(['team_id', 'success', 'sent_at', 'reply_id']);
        });

        Schema::table('replies', function (Blueprint $table) {
            $table->index(['team_id', 'stop', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
