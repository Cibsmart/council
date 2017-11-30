<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadTest extends TestCase
{
    use DatabaseMigrations;

    private $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create(Thread::class);
    }
    /**
     * A User Can Read All Threads
     *
     * @test
     * @return void
     */
    public function aUserCanReadAllThreads()
    {
        $this->get('threads')
            ->assertSee($this->thread->title);
    }

    /**
     * A User Can Read A Single Thread
     *
     * @test
     * @return void
     */
    public function aUserCanReadASingleThread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /**
     * A User Can Read Replies That are Associated With A Thread
     *
     * @test
     * @return void
     */
    public function aUserCanReadRepliesThatAreAssociatedWithAThread()
    {
        $reply = create(Reply::class,['thread_id' => $this->thread->id]);
        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }
    
    /**
     * A User can Filter Threads According to a Channel
     *
     * @test
     * @return void
     */
    public function aUserCanFilterThreadsAccordingToAChannel()
    {
        $channel = create(Channel::class);
        $threadInChannel = create(Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(Thread::class);

        $this->get(route('channel.index', $channel->slug))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }
    
    /**
     * A User Can Filter Threads by any Username
     *
     * @test
     * @return void
     */
    public function aUserCanFilterThreadsByAnyUsername()
    {
        $this->signIn(create(User::class, [ 'name' => 'JohnDoe']));

        $threadByJohn = create(Thread::class,['user_id' => auth()->id()]);
        $threadNotByJohn = create(Thread::class);

        $this->get(route('threads.index',['by' => 'JohnDoe']))
            ->assertSee($threadByJohn->title)
            ->assertDontSee($threadNotByJohn->title);
    }
}
