<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use function route;
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


    /**
     * A User Can Filter Threads by Popularity
     *
     * @test
     * @return void
     */
    public function aUserCanFilterThreadsByPopularity()
    {
        $threadsWithTwoReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadsWithTwoReplies->id], 2);

        $threadsWithThreeReplies = create(Thread::class);
        create(Reply::class, ['thread_id' => $threadsWithThreeReplies->id], 3);

        $threadsWithNoReplies = $this->thread;

        $response = $this->getJson(route('threads.index', ['popular' => 1]))->json();

        $this->assertEquals([3,2,0], array_column($response['data'], 'replies_count'));
    }


    /**
     * A User Can Filter Threads by those that are unanswered
     *
     * @test
     * @return void
     */
    public function aUserCanFilterThreadsByThoseThatAreUnanswered()
    {
        $thread = create(Thread::class);

        create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->getJson(route('threads.index', ['unanswered' => 1]))->json();

        $this->assertCount(1, $response['data']);
    }


    /**
     * A User Can Request All Replies for a Given thread
     *
     * @test
     * @return void
     */
    public function aUserCanRequestAllRepliesForAGivenThread()
    {
        $thread = create(Thread::class);

        create(Reply::class, ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();

        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }
}
