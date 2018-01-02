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
    
    
    /**
     * A User Can Determine their avatar path
     *
     * @test
     * @return void
     */
    public function aUserCanDetermineTheirAvatarPath()
    {
        $user = create(User::class);

        $this->assertEquals('/storage/avatars/default_avatar.jpg', $user->avatar_path);

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals('/storage/avatars/me.jpg', $user->avatar_path);
    }
}
