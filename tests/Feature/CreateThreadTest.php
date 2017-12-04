<?php

namespace Tests\Feature;

use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use function create;
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

    /**
     * Guest Cannot Delete Threads
     *
     * @test
     * @return void
     */
    public function guestCannotDeleteThreads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $response = $this->delete($thread->path());

        $response->assertRedirect('login');
    }
    
    /**
     * A Thread can be Deleted
     *
     * @test
     * @return void
     */
    public function aThreadCanBeDeleted()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id ])
            ->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
    
    /**
     * Threads May Only be Deleted by those who have Permission
     *
     * @test
     * @return void
     */
    public function threadsMayOnlyBeDeletedByThoseWhoHavePermission()
    {
        //TODO
    }

    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()
            ->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store'), $thread->toArray());
    }
}
