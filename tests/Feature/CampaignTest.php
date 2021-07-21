<?php

namespace Tests\Feature;

use App\Account;
use App\Campaign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_gets_a_single_account_id()
    {
        $account = factory(Account::class)->create();

        $campaign = factory(Campaign::class)->create([
            'reply_account_id' => $account->id,
        ]);

        $this->assertEquals($campaign->getReplyAccount()->id, $account->id);
    }

    /** @test */
    public function it_gets_a_random_account_id_from_a_group()
    {
        $account = factory(Account::class)->create([
            'is_group' => true,
        ]);

        $attachedAccount = factory(Account::class)->create();
        $anotherAttachedAccount = factory(Account::class)->create();
        $anotherAccount = factory(Account::class)->create();

        $account->attachAccount($attachedAccount);
        $account->attachAccount($anotherAttachedAccount);

        $campaign = factory(Campaign::class)->create([
            'reply_account_id' => $account->id,
        ]);

        $this->assertTrue(in_array($campaign->getReplyAccount()->id, [$attachedAccount->id, $anotherAttachedAccount->id]));

        $this->assertEquals([$attachedAccount->id, $anotherAttachedAccount->id], $campaign->replyAccount->accounts->map->id->all());
    }
}
