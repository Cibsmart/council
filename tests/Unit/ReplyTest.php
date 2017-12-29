<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use function create;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * Reply Has An Owner
     *
     * @test
     * @return void
     */
    public function replyHasAnOwner()
    {
        $reply = create(Reply::class);
        $this->assertInstanceOf(User::class, $reply->owner);
    }


    /**
     * It Knows if it was Just Published
     *
     * @test
     * @return void
     */
    public function itKnowsIfItWasJustPublished()
    {
       $reply = create(Reply::class);

       $this->assertTrue($reply->wasJustPublished());

       $reply->created_at = Carbon::now()->subMonth();

       $this->assertFalse($reply->wasJustPublished());
    }
    
    
    /**
     * It can Detect All mentioned Users In the Reply
     *
     * @test
     * @return void
     */
    public function itCanDetectAllMentionedUsersInTheReply()
    {
       $reply = create(Reply::class,
           ['body' => '@JaneDoe wants to talk to @JohnDoe']);

       $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }
}
