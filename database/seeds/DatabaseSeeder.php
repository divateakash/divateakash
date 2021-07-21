<?php

use App\Account;
use App\Catalog;
use App\Provider;
use App\Team;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $team = factory(Team::class)->create();

        $user = factory(User::class)->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
            'team_id' => $team->id,
            'is_admin' => true,
        ]);

        $list = factory(Catalog::class)->create(['team_id' => $team->id]);

        $provider = factory(Provider::class)->create(['team_id' => $team->id, 'name' => 'Gorilla', 'provider' => 'gorilla', 'cost' => 0.005, 'username' => 'c2bc16aa39cf4ecb8ac4697b4bc9a7f7', 'password' => 'b2b9cd4e799e440f8315b74948342a0b']);

        $account = factory(Account::class)->create(['name' => 'Gorilla', 'provider_id' => $provider->id, 'team_id' => $team->id]);
    }
}
