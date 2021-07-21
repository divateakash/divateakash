<?php

namespace Tests\Feature\Support;

use App\Carrier;
use App\Support\CarrierLookup;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CarrierLookupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_correctly_gets_the_carrier()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->id();
            $table->string('NPA');
            $table->string('NXX');
            $table->string('BLOCK_ID');
            $table->string('LTYPE');
            $table->string('STATE');
            $table->string('WC');
            $table->string('OCN');
            $table->string('OLSON');
        });

        Schema::create('ocn', function (Blueprint $table) {
            $table->id();
            $table->string('OCN');
            $table->string('CommonName');
        });

        $number = '2547096981';

        $carrier = Carrier::whereName('Verizon')->first();

        DB::table('phones')->insert([
            'NPA' => '254',
            'NXX' => '709',
            'BLOCK_ID' => '6',
            'LTYPE' => 'C',
            'STATE' => 'TX',
            'WC' => 'Houston',
            'OCN' => '1234',
            'OLSON' => 'America/Chicago',
        ]);

        DB::table('ocn')->insert([
            'OCN' => '1234',
            'CommonName' => 'Verizon Wireless',
        ]);

        $lookup = CarrierLookup::phone($number);

        $this->assertEquals('America/Chicago', $lookup->timezone);
        $this->assertEquals('TX', $lookup->region);
        $this->assertEquals('Verizon Wireless', $lookup->carrier);
        $this->assertEquals($carrier->id, $lookup->carrierObject()->id);
    }
}
