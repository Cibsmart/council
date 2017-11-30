<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Guests May Not Create Threads
     *
     * @test
     * @return void
     */
    public function guestsMayNotCreateThreads()
    {
        $this->withExceptionHandling();

        $this->get(route('threads.create'))
            ->assertRedirect('/login');

        $this->post(route('threads.store'))
            ->assertRedirect('/login');
    }

    /**
     * An Authenticated User Can Create New Forum Threads
     *
     * @test
     * @return void
     */
    public function anAuthenticatedUserCanCreateNewForumThreads()
    {
        $this->signIn();

        $thread = make(Thread::class);

        $response = $this->post(route('threads.store'), $thread->toArray());

        $this->get($response->headers->get('location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * A Thread Requires A Title
     *
     * @test
     * @return void
     */
    public function aThreadRequiresATitle()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /**
     * A Thread Requires A Body
     *
     * @test
     * @return void
     */
    public function aThreadRequiresABody()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /**
     * A Thread Requires A Body
     *
     * @test
     * @return void
     */
    public function aThreadRequiresAValidChannel()
    {
        factory(Channel::class, 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 3])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()
            ->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store'), $thread->toArray());
    }
}
