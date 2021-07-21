<?php

use App\ReplyWord;
use App\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->string('type');
            $table->unsignedInteger('team_id');
            $table->timestamps();
            $table->index(['word', 'type']);
        });

        foreach (Team::all() as $team) {
            foreach (bad_words() as $badWord) {
                $team->replyWords()->create(['word' => $badWord, 'type' => ReplyWord::BAD]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reply_words');
    }
}
