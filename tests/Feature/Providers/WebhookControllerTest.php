<?php

namespace Tests\Feature\Providers;

use App\Account;
use App\Catalog;
use App\Jobs\AddLeadToCatalog;
use App\Jobs\ProcessPendingOutbound;
use App\Message;
use App\Outbound;
use App\Reply;
use App\Suppression;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_reply()
    {
        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
        ]);

        $outbound->provider->update(['name' => 'gorilla']);

        $request = $this->post(route('providers.webhook', 'gorilla'), [
            'from' => $outbound->to,
            'to' => $outbound->from,
            'body' => 'Something something',
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reply = Reply::latest('id')->first();

        $this->assertEquals('Something something', $reply->content);
        $this->assertEquals(number($outbound->to), $reply->from);
        $this->assertEquals(number($outbound->from), $reply->to);
        $this->assertEquals($outbound->id, $reply->outbound->id);
        $this->assertEquals($outbound->campaign->id, $reply->campaign->id);
        $this->assertEquals($outbound->team->id, $reply->team->id);
    }

    /** @test */
    public function it_supresses_a_lead()
    {
        $this->withoutExceptionHandling();
        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
        ]);

        $outbound->provider->update(['name' => 'gorilla']);

        $lead = $outbound->lead;
        $lead->team_id = $outbound->team_id;
        $lead->phone = $outbound->to;
        $lead->save();

        $this->assertNull($lead->suppressed_at);

        $request = $this->post(route('providers.webhook', 'gorilla'), [
            'from' => $outbound->to,
            'to' => $outbound->from,
            'body' => 'StOp sending this',
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reply = Reply::latest('id')->first();

        $this->assertEquals('StOp sending this', $reply->content);
        $this->assertEquals(number($outbound->to), $reply->from);
        $this->assertEquals(number($outbound->from), $reply->to);
        $this->assertEquals($outbound->id, $reply->outbound->id);
        $this->assertEquals($outbound->campaign->id, $reply->campaign->id);
        $this->assertEquals($outbound->team->id, $reply->team->id);
        $this->assertTrue($reply->stop);
        $this->assertNotNull($lead->fresh()->suppressed_at);

        $suppression = Suppression::latest('id')->first();
        $this->assertEquals($lead->phone, $suppression->phone);
    }

    /** @test */
    public function it_sends_a_2nd_message_back_and_saves_the_replier()
    {
        $this->withoutExceptionHandling();

        Bus::fake();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
        ]);

        $replyAccount = factory(Account::class)->create();
        $replyMessage = factory(Message::class)->create();
        $replyMessageGroup = $replyMessage->messageGroup;

        $repliersList = factory(Catalog::class)->create();

        $replyAccount->provider->update(['provider' => 'gorilla']);
        $outbound->provider->update(['name' => 'gorilla']);

        $campaign = $outbound->campaign;
        $campaign->update([
            'message_type' => 'keyword_reply',
            'reply_message_group_id' => $replyMessageGroup->id,
            'reply_account_id' => $replyAccount->id,
            'repliers_catalog_id' => $repliersList->id,
        ]);

        $this->assertEquals(0, $repliersList->leads()->count());

        $outbound->team->replyWords()->create([
            'word' => 'yes',
            'type' => 'good',
        ]);

        $request = $this->post(route('providers.webhook', 'gorilla'), [
            'from' => $outbound->to,
            'to' => $outbound->from,
            'body' => 'Yes',
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reply = Reply::latest('id')->first();

        $repliedOutbound = $reply->repliedOutbounds()->first();

        Bus::assertDispatched(ProcessPendingOutbound::class, fn($job) =>
            $job->outbound->id === $repliedOutbound->id
        );

        Bus::assertDispatched(AddLeadToCatalog::class, fn($job) =>
            $job->catalog->id == $repliersList->id
        );
    }

    /** @test */
    public function it_adds_a_2nd_message_with_no_account_when_there_isnt_any()
    {
        $this->withoutExceptionHandling();

        Bus::fake();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
        ]);

        $replyAccount = factory(Account::class)->create([
            'is_group' => true,
        ]);

        $replyMessage = factory(Message::class)->create();
        $replyMessageGroup = $replyMessage->messageGroup;

        $repliersList = factory(Catalog::class)->create();

        $outbound->provider->update(['name' => 'gorilla']);

        $campaign = $outbound->campaign;
        $campaign->update([
            'message_type' => 'keyword_reply',
            'reply_message_group_id' => $replyMessageGroup->id,
            'reply_account_id' => $replyAccount->id,
            'repliers_catalog_id' => $repliersList->id,
        ]);

        $this->assertEquals(0, $repliersList->leads()->count());

        $request = $this->post(route('providers.webhook', 'gorilla'), [
            'from' => $outbound->to,
            'to' => $outbound->from,
            'body' => 'Yes',
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $reply = Reply::latest('id')->first();

        $repliedOutbound = $reply->repliedOutbounds()->first();
        $this->assertNull($repliedOutbound->account);

        Bus::assertDispatched(ProcessPendingOutbound::class, fn($job) =>
            $job->outbound->id === $repliedOutbound->id
        );

        Bus::assertDispatched(AddLeadToCatalog::class, fn($job) =>
            $job->catalog->id == $repliersList->id
        );
    }
}
