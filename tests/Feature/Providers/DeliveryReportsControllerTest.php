<?php

namespace Tests\Feature\Providers;

use App\Account;
use App\Message;
use App\MessageGroup;
use App\Outbound;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeliveryReportsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_handles_a_successful_delivery_report_txtria()
    {
        $this->withoutExceptionHandling();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
        ]);

        $outbound->provider->update(['name' => 'txtria']);

        $request = $this->post(route('providers.delivery_report', 'txtria'), [
            'source' => number($outbound->from),
            'destination' => number($outbound->to),
            'time' => ($date = now())->toDateTimeString(),
            'error' => '0',
            'status' => 'Delivered',
            'send_status' => 'Delivered',
            'tracking_id' => $outbound->response,
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertTrue($outbound->delivered());
        $this->assertEquals($date, $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'error' => '0',
            'status' => 'Delivered',
            'send_status' => 'Delivered',
            'tracking_id' => $outbound->response,
        ], $outbound->deliveryReport->meta);
    }

    /** @test */
    public function it_handles_a_successful_delivery_report_gorilla()
    {
        $this->withoutExceptionHandling();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
        ]);

        $outbound->provider->update(['name' => 'gorilla']);

        $request = $this->post(route('providers.delivery_report', 'gorilla'), [
            'id' => $outbound->response,
            'action' => 'routeStatus',
            'detail' => 'DELIVERED',
            'reason' => 'Success',
            'carrierID' => '1025',
            'carrierName' => 'AT&T Wireless',
            'validNumber' => true,
            'numberType' => 'M',
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertTrue($outbound->delivered());
        $this->assertEquals(now(), $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'id' => $outbound->response,
            'action' => 'routeStatus',
            'detail' => 'DELIVERED',
            'reason' => 'Success',
        ], $outbound->deliveryReport->meta);
    }

    /** @test */
    public function it_handles_a_spam_error()
    {
        $this->withoutExceptionHandling();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
        ]);

        $outbound->provider->update(['name' => 'txtria']);

        $message = $outbound->message;

        $request = $this->post(route('providers.delivery_report', 'txtria'), [
            'source' => number($outbound->from),
            'destination' => number($outbound->to),
            'time' => ($date = now())->toDateTimeString(),
            'error' => '112',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertFalse($outbound->delivered());
        $this->assertEquals($date, $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'error' => '112',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ], $outbound->deliveryReport->meta);

        $this->assertTrue($message->fresh()->trashed());
    }

    /** @test */
    public function it_handles_a_69_as_spam()
    {
        $this->withoutExceptionHandling();

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
        ]);

        $outbound->provider->update(['name' => 'txtria']);

        $message = $outbound->message;

        $request = $this->post(route('providers.delivery_report', 'txtria'), [
            'source' => number($outbound->from),
            'destination' => number($outbound->to),
            'time' => ($date = now())->toDateTimeString(),
            'error' => '69',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertFalse($outbound->delivered());
        $this->assertEquals($date, $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'error' => '69',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ], $outbound->deliveryReport->meta);

        $this->assertTrue($message->fresh()->trashed());
    }

    /** @test */
    public function it_detaches_an_account_when_getting_a_spam_error()
    {
        $this->withoutExceptionHandling();

        $account = factory(Account::class)->create();
        $accountMessageGroup = factory(MessageGroup::class)->create();
        $accountMessage = $accountMessageGroup->messages()->save(factory(Message::class)->make());

        $accountGroup = factory(Account::class)->create([
            'is_group' => true,
        ]);
        $accountGroup->attachAccount($account);

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
            'account_id' => $account->id,
            'reply_id' => 123,
        ]);

        $outbound->campaign->update(['reply_account_id' => $accountGroup->id]);

        $outbound->provider->update(['name' => 'txtria']);

        $message = $outbound->message;

        $this->assertEquals(1, $accountGroup->accounts()->count());

        $request = $this->post(route('providers.delivery_report', 'txtria'), [
            'source' => number($outbound->from),
            'destination' => number($outbound->to),
            'time' => ($date = now())->toDateTimeString(),
            'error' => '112',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertFalse($outbound->delivered());
        $this->assertEquals($date, $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'error' => '112',
            'status' => 'Rejected',
            'send_status' => 'Rejected',
            'tracking_id' => $outbound->response,
        ], $outbound->deliveryReport->meta);

        $this->assertTrue($message->fresh()->trashed());

        $this->assertEquals(0, $accountGroup->accounts()->count());
        $this->assertEquals("Account {$account->name} / {$account->id} detached due to Spam - 112", $accountGroup->activityLogs()->latest('id')->first()->content);
    }

    /** @test */
    public function it_does_not_detach_an_account_on_a_good_reply()
    {
        $this->withoutExceptionHandling();

        $account = factory(Account::class)->create();
        $accountMessageGroup = factory(MessageGroup::class)->create();
        $accountMessage = $accountMessageGroup->messages()->save(factory(Message::class)->make());

        $accountGroup = factory(Account::class)->create([
            'is_group' => true,
        ]);
        $accountGroup->attachAccount($account);

        $outbound = factory(Outbound::class)->create([
            'from' => '11234561234',
            'to' => '19993331234',
            'response' => (string) Str::uuid(),
            'account_id' => $account->id,
            'reply_id' => 123,
        ]);

        $outbound->campaign->update(['reply_account_id' => $accountGroup->id]);

        $outbound->provider->update(['name' => 'txtria']);

        $message = $outbound->message;

        $this->assertEquals(1, $accountGroup->accounts()->count());

        $request = $this->post(route('providers.delivery_report', 'txtria'), [
            'source' => number($outbound->from),
            'destination' => number($outbound->to),
            'time' => ($date = now())->toDateTimeString(),
            'error' => '0',
            'status' => 'Delivered',
            'send_status' => 'Delivered',
            'tracking_id' => $outbound->response,
        ]);

        $request
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $outbound = $outbound->fresh();

        $this->assertTrue($outbound->delivered());
        $this->assertEquals($date, $outbound->deliveryReport->delivered_at);
        $this->assertEquals([
            'error' => 0,
            'status' => 'Delivered',
            'send_status' => 'Delivered',
            'tracking_id' => $outbound->response,
        ], $outbound->deliveryReport->meta);

        $this->assertFalse($message->fresh()->trashed());

        $this->assertEquals(1, $accountGroup->accounts()->count());
    }
}
