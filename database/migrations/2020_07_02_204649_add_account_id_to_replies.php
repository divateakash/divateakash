<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToReplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->nullable();
            $table->index(['account_id', 'stop', 'created_at']);
            $table->index(['to', 'stop', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            //
        });
    }
}
