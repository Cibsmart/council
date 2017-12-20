<?php

namespace Tests\Feature;

use App\Thread;
use function auth;
use function create;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    
    /**
     * A User Can Subscribe to a Test
     *
     * @test
     * @return void
     */
    public function aUserCanSubscribeToATest()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some Reply Here'
        ]);

        $this->assertCount(1, auth()->user()->notifications);
    }
    
    
    /**
     * A User Can UnSubscribe From Threads
     *
     * @test
     * @return void
     */
    public function aUserCanUnSubscribeFromThreads()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');

        $this->assertFalse($thread->isSubscribedTo);
    }
}