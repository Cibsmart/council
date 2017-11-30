<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Auth\AuthenticationException;
use function route;
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

        $thread = create(Thread::class);

        $this->post(route('threads.store'), $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
