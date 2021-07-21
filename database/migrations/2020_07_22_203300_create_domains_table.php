<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->index();
            $table->string('status')->index();
            $table->string('points_to')->nullable();
            $table->timestamp('dns_last_updated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('domain_group_id');
            $table->unsignedBigInteger('domain_provider_id');
            $table->string('error')->nullable();
            $table->timestamp('errored_at')->nullable();
            $table->unsignedBigInteger('team_id');
            $table->timestamps();
            $table->index(['domain_group_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domains');
    }
}
