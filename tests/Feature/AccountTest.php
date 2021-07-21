<?php

namespace Tests\Feature;

use App\Account;
use App\Number;
use App\Reply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_the_average_opt_out_in_the_last_24_h()
    {
        $account = factory(Account::class)->create();
        $number = $account->numbers()->save(factory(Number::class)->make());

        $goodReply = factory(Reply::class)->create([
            'account_id' => $account->id,
            'to' => $number->number,
            'stop' => false,
        ]);

        $goodReply = factory(Reply::class)->create([
            'account_id' => $account->id,
            'to' => $number->number,
            'stop' => true,
        ]);

        $this->assertEquals(0.5, $account->averageOptOut(24));
        $this->assertEquals(0.5, $number->averageOptOut(24));
    }

    /** @test */
    public function it_calculates_zero_opt_outs()
    {
        $account = factory(Account::class)->create();
        $number = $account->numbers()->save(factory(Number::class)->make());

        $this->assertEquals(0.0, $account->averageOptOut(24));
        $this->assertEquals(0.0, $number->averageOptOut(24));
    }
}
