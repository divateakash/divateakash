<?php

namespace Tests\Feature\Jobs;

use App\Jobs\ProcessPendingOutbound;
use App\Message;
use App\Messaging\Providers\Provider;
use App\Number;
use App\Outbound;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Jobs\ProcessPendingOutboundTest;
use Tests\TestCase;

class ProcessPendingOutboundTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_reschedules_a_message_past_their_send_time_to_the_next_day()
    {
        $pastSendingHours = today()->timezone('America/Chicago')->hour(21)->timezone('UTC');
        $expectedSendHour = today()->timezone('America/Chicago')->addDay()->hour(8)->timezone('UTC');

        Carbon::setTestNow($pastSendingHours);

        $outbound = factory(Outbound::class)->create(['send_at' => now(), 'sent_at' => null]);
        $outbound->lead->update(['timezone' => 'America/Chicago']);
        $outbound->account->provider->update(['provider' => 'gorilla']);

        (new ProcessPendingOutbound($outbound, $outbound->account))->handle();

        $outbound->refresh();
        $this->assertEquals($expectedSendHour, $outbound->send_at);
    }

    /** @test */
    public function it_reschedules_a_message_before_their_send_time_to_the_same_day()
    {
        $beforeSendingHours = today()->timezone('America/Chicago')->hour(7)->timezone('UTC');
        $expectedSendHour = today()->timezone('America/Chicago')->hour(8)->timezone('UTC');

        Carbon::setTestNow($beforeSendingHours);

        $outbound = factory(Outbound::class)->create(['send_at' => now(), 'sent_at' => null]);
        $outbound->lead->update(['timezone' => 'America/Chicago']);
        $outbound->account->provider->update(['provider' => 'gorilla']);

        (new ProcessPendingOutbound($outbound, $outbound->account))->handle();

        $outbound->refresh();
        $this->assertEquals($expectedSendHour, $outbound->send_at);
    }

    /** @test */
    public function it_sends_a_message()
    {
        $providerFake = Provider::fake();

        $outbound = factory(Outbound::class)->create(['send_at' => now(), 'sent_at' => null]);
        $outbound->lead->update(['timezone' => 'America/Chicago']);
        $outbound->account->provider->update(['provider' => 'gorilla']);

        $pastSendingHours = today()->timezone('America/Chicago')->hour(21)->timezone('UTC');
        $expectedSendHour = today()->timezone('America/Chicago')->addDay()->hour(9)->timezone('UTC');

        Carbon::setTestNow($pastSendingHours->subHours(2));

        $outbound->messageGroup->messages()->save(factory(Message::class)->make());
        $outbound->account->numbers()->save(factory(Number::class)->make());

        (new ProcessPendingOutbound($outbound, $outbound->account))->handle();

        $outbound->refresh();
        $this->assertTrue($outbound->processed);
        $this->assertTrue($outbound->success);
        $this->assertEquals(now(), $outbound->sent_at);

        $providerFake->assertMessageSentTo($outbound->to);

        $sentMessage = $providerFake->sentMessages()->first();

        $this->assertEquals($outbound->number->number, $sentMessage['from']);
        $this->assertEquals($outbound->to, $sentMessage['to']);
        $this->assertEquals($outbound->content, $sentMessage['message']);

        Provider::restore();
    }

    /** @test */
    public function it_disables_a_campaign_if_it_lacks_messages()
    {
        $providerFake = Provider::fake();

        $outbound = factory(Outbound::class)->create(['send_at' => now(), 'sent_at' => null, 'processed' => false]);
        $outbound->lead->update(['timezone' => 'America/Chicago']);
        $outbound->account->provider->update(['provider' => 'gorilla']);

        $pastSendingHours = today()->timezone('America/Chicago')->hour(21)->timezone('UTC');
        $expectedSendHour = today()->timezone('America/Chicago')->addDay()->hour(9)->timezone('UTC');

        Carbon::setTestNow($pastSendingHours->subHours(2));

        $outbound->messageGroup->messages()->save(factory(Message::class)->make());
        $outbound->account->numbers()->save(factory(Number::class)->make());

        $outbound->messageGroup->messages->each->delete();

        (new ProcessPendingOutbound($outbound, $outbound->account))->handle();

        $outbound->refresh();
        $this->assertFalse($outbound->processed);
        $this->assertNull($outbound->sent_at);
        $this->assertCount(0, $providerFake->sentMessages());

        Provider::restore();
    }
}
