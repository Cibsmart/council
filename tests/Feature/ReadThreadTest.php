<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
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
        $this->get('threads/' . $this->thread->id)
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
        $this->get('threads/' . $this->thread->id)
            ->assertSee($reply->body);
    }
}
