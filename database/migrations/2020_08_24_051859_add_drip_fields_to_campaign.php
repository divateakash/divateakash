<?php

use App\Campaign;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDripFieldsToCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('type')->default(Campaign::Campaign);
            $table->unsignedInteger('drip_wait_hours')->nullable();
            $table->boolean('drip_skip_weekends')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            //
        });
    }
}
