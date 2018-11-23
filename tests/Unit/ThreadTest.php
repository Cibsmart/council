<?php

namespace Tests\Unit;

use App\Channel;
use App\Notifications\ThreadWasUpdated;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class ThreadTest
 *
 * @package Tests\Unit
 */
class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $_thread;

    /**
     * Setup Method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_thread = create(Thread::class);
    }

    /**
     * A Thread Has A Path
     *
     * @test
     * @return void
     */
    public function aThreadHasAPath()
    {
        $thread = create(Thread::class);

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->slug}",
            $thread->path()
        );
    }

    /**
     * A Thread Has a Creator
     *
     * @test
     * @return void
     */
    public function aThreadHasACreator()
    {
        $this->assertInstanceOf(User::class, $this->_thread->creator);
    }

    /**
     * A Thread Has Replies
     *
     * @test
     * @return void
     */
    public function AThreadHasReplies()
    {
        $this->assertInstanceOf(Collection::class, $this->_thread->replies);
    }

    /**
     * A Thread Can Add A Reply
     *
     * @test
     * @return void
     */
    public function aThreadCanAddAReply()
    {
        $this->_thread->addReply([
            'body'    => 'Foobar',
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->_thread->replies);
    }

    /**
     * A Thread Notifies all Registered Subscribers when a Reply is added
     *
     * @test
     * @return void
     */
    public function aThreadNotifiesAllRegisteredSubscribersWhenAReplyIsAdded()
    {
        Notification::fake();

        $this->signIn()
            ->_thread
            ->subscribe()
            ->addReply([
                'body'    => 'Foobar',
                'user_id' => 1,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /**
     * A Thread Belongs to a Channel
     *
     * @test
     * @return void
     */
    public function aThreadBelongsToAChannel()
    {
        $thread = make(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }

    /**
     * A Thread Can be Subscribed to
     *
     * @test
     * @return void
     */
    public function aThreadCanBeSubscribedTo()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count()
        );
    }

    /**
     * A Thread can be UnSubscribed from
     *
     * @test
     * @return void
     */
    public function aThreadCanBeUnSubscribedFrom()
    {
        $thread = create(Thread::class);

        $thread->subscribe($userId = 1);

        $thread->unSubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /**
     * It Knows If The Authenticated User Is Subscribed To It
     *
     * @test
     * @return void
     */
    public function itKnowsIfTheAuthenticatedUserIsSubscribedToIt()
    {
        $thread = create(Thread::class);

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /**
     * A Thread Can Check if the Authenticated User Has Read All Replies
     *
     * @test
     * @return void
     */
    public function aThreadCanCheckIfTheAuthenticatedUserHasReadAllReplies()
    {
        $this->signIn();

        $thread = create(Thread::class);

        tap(auth()->user(), function ($user) use ($thread) {
            $this->assertTrue($thread->hasUpdatesFor($user));

            $user->read($thread);

            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /**
     * A Thread Records Each Visit
     *
     * @test
     * @return void
     */
//    public function aThreadRecordsEachVisit()
//    {
//       $thread = make(Thread::class, ['id' => 1]);
//
//       $thread->visits()->reset();
//
//        $this->assertSame(0, $thread->visits()->count());
//
//        $thread->visits()->record();
//
//       $this->assertEquals(1, $thread->visits()->count());
//
//       $thread->visits()->record();
//
//       $this->assertEquals(2, $thread->visits()->count());
//    }
}
