<?php

namespace Tests\Feature;

use App\Account;
use App\Message;
use App\MessageGroup;
use App\Outbound;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_a_random_message()
    {
        $account = factory(Account::class)->create();

        $messageGroup = factory(MessageGroup::class)->create();
        $message = $messageGroup->messages()->save(factory(Message::class)->make());

        $outbound = factory(Outbound::class)->create([
            'account_id' => $account->id,
            'message_group_id' => $messageGroup->id,
        ]);

        $this->assertEquals($message->fresh(), $outbound->getRandomMessage());
    }

    /** @test */
    public function it_uses_the_account_message_group_when_there_is_one()
    {
        $account = factory(Account::class)->create();
        $accountMessageGroup = factory(MessageGroup::class)->create();
        $accountMessage = $accountMessageGroup->messages()->save(factory(Message::class)->make());
        $account->update(['message_group_id' => $accountMessageGroup->id]);

        $messageGroup = factory(MessageGroup::class)->create();
        $message = $messageGroup->messages()->save(factory(Message::class)->make());

        $outbound = factory(Outbound::class)->create([
            'account_id' => $account->id,
            'message_group_id' => $messageGroup->id,
        ]);

        $this->assertEquals($accountMessage->id, $outbound->getRandomMessage()->id);
        $this->assertEquals($accountMessage->content, $outbound->getRandomMessage()->content);
    }
}
