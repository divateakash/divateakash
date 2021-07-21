
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutboundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outbounds', function (Blueprint $table) {
            $table->id();
            $table->string('from')->nullable();
            $table->string('to');
            $table->decimal('cost', 8, 5);
            $table->string('hash')->nullable();
            $table->string('link')->nullable();
            $table->boolean('processed')->index();
            $table->boolean('success')->nullable();
            $table->string('error')->nullable();
            $table->timestamp('send_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->string('response')->nullable()->index();
            $table->string('content')->nullable();
            $table->unsignedBigInteger('campaign_id')->index();
            $table->unsignedBigInteger('offer_id');
            $table->unsignedBigInteger('lead_id');
            $table->unsignedBigInteger('account_id')->nullable()->index();
            $table->unsignedBigInteger('message_group_id');
            $table->unsignedBigInteger('message_id')->nullable();
            $table->unsignedBigInteger('team_id')->index();
            $table->unsignedBigInteger('number_id')->nullable()->index();
            $table->unsignedBigInteger('reply_id')->nullable();
            $table->timestamps();
            $table->index(['account_id', 'processed']);
            $table->index(['campaign_id', 'success']);
            $table->index(['campaign_id', 'processed']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outbounds');
    }
}
