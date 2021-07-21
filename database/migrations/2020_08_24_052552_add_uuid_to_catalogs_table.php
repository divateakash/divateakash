<?php

use App\Catalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddUuidToCatalogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('catalogs', function (Blueprint $table) {
            $table->string('uuid')->nullable();
        });

        Catalog::all()->each(fn($catalog) => $catalog->update(['uuid' => (string) Str::uuid()]));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('catalogs', function (Blueprint $table) {
            //
        });
    }
}
