<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A User Can Fetch Their Most Recent Reply
     *
     * @test
     * @return void
     */
    public function aUserCanFetchTheirMostRecentReply()
    {
        $user = create(User::class);

        $reply = create(Reply::class, ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }
}
