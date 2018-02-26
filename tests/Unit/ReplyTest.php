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
       $reply = new Reply(
           ['body' => '@JaneDoe! wants to talk to @JohnDoe?']);

       $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }
    
    
    /**
     * It wraps Mentioned Usernames in the body withing anchor tags
     *
     * @test
     * @return void
     */
    public function itWrapsMentionedUsernamesInTheBodyWithingAnchorTags()
    {
        $reply = new Reply(
            ['body' => 'Hello @Jane-Doe?']);

        $this->assertEquals(
            'Hello <a href="/profiles/Jane-Doe">@Jane-Doe</a>?',
            $reply->body
        );
    }
    
    
    /**
     * It Knows If It is the Best Reply
     *
     * @test
     * @return void
     */
    public function itKnowsIfItIsTheBestReply()
    {
        $reply = create(Reply::class);

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->fresh()->isBest());
    }
}
