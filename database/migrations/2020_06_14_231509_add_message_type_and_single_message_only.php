<?php

use App\Enums\MessageGroupType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMessageTypeAndSingleMessageOnly extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_groups', function (Blueprint $table) {
            $table->string('type')->default(MessageGroupType::First);
            $table->boolean('single_message_only')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_groups', function (Blueprint $table) {
            //
        });
    }
}
