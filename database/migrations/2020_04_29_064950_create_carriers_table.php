<?php

use App\Carrier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $carriers = ['AT&T', 'Verizon', 'Sprint', 'T-Mobile', 'U.S Cellular', 'MetroPCS', 'Others'];

        foreach ($carriers as $carrier) {
            Carrier::create(['name' => $carrier]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carriers');
    }
}
