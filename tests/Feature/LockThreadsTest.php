<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use function route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * Non Administrators May Not Lock Threads
     *
     * @test
     * @return void
     */
    public function nonAdministratorsMayNotLockThreads()
    {
        $this->signIn()->withExceptionHandling();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }


    /**
     * An Administrator May Lock a Thread
     *
     * @test
     * @return void
     */
    public function anAdministratorMayLockAThread()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->signIn($user);

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue(
            $thread->fresh()->locked,
            'Failed Asserting that the thread was Locked');
    }
    
    /**
     * An Administrator Can Unlock Threads
     *
     * @test
     * @return void
     */
    public function anAdministratorCanUnlockThreads()
    {
        $user = factory(User::class)->states('administrator')->create();

        $this->signIn($user);

        $thread = create(Thread::class, ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }


    /**
     * Once Locked A Thread May Not Receive New Replies
     *
     * @test
     * @return void
     */
    public function onceLockedAThreadMayNotReceiveNewReplies()
    {
        $this->signIn();

        $thread = create(Thread::class, ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}