<?php

namespace Tests\Feature;

use App\Lead;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_determines_if_the_lead_is_under_send_hours()
    {
        $lead = factory(Lead::class)->make([
            'timezone' => 'America/Chicago',
        ]);

        $startSendingHour = 13; // 8am
        $endSendingHour = 2; // 9pm

        Carbon::setTestNow(now()->hour(12));
        $this->assertFalse($lead->underSendHours());

        Carbon::setTestNow(now()->hour(13));
        $this->assertTrue($lead->underSendHours());

        Carbon::setTestNow(now()->hour(23));
        $this->assertTrue($lead->underSendHours());

        Carbon::setTestNow(now()->addDay()->hour(1));
        $this->assertTrue($lead->underSendHours());

        Carbon::setTestNow(now()->hour(1)->minute(59));
        $this->assertTrue($lead->underSendHours());

        Carbon::setTestNow(now()->hour(2));
        $this->assertFalse($lead->underSendHours());
    }
}
