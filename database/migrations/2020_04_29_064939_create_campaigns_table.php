<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->string('link_type');
            $table->string('message_type');
            $table->json('carriers')->nullable();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('reply_account_id')->nullable();
            $table->unsignedBigInteger('message_group_id');
            $table->unsignedBigInteger('reply_message_group_id')->nullable();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('catalog_id');
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
        Schema::dropIfExists('campaigns');
    }
}
