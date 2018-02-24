<?php

namespace Tests\Feature;

use App\Activity;
use App\Channel;
use App\Reply;
use App\Thread;
use App\User;
use function create;
use function factory;
use function get_class;
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
            ->assertRedirect(route('login'));

        $this->post(route('threads.store'))
            ->assertRedirect(route('login'));

    }
    
    /**
     * New Users Must First Confirm Their Email Address Before Creating Threads
     *
     * @test
     * @return void
     */
    public function newUsersMustFirstConfirmTheirEmailAddressBeforeCreatingThreads()
    {
        $user = factory(User::class)->states('unconfirmed')->create();

        $this->withExceptionHandling()->signIn($user);

        $thread = make(Thread::class);

        $this->post(route('threads.store'), $thread->toArray())
            ->assertRedirect(route('threads.index'))
            ->assertSessionHas('flash', 'You must first Confirm your email address.');
    }
    

    /**
     * A  User Can Create New Forum Threads
     *
     * @test
     * @return void
     */
    public function AUserCanCreateNewForumThreads()
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
     * A Thread Requires a Unique Slug
     *
     * @test
     * @return void
     */
    public function aThreadRequiresAUniqueSlug()
    {
        $this->signIn();

        $thread = create(Thread::class, ['title' => 'Foo Title', 'slug' => 'foo-title']);

        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads.store'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads.store'), $thread->toArray());

        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }


    /**
     * Unauthorized Users May Not Delete Threads
     *
     * @test
     * @return void
     */
    public function unauthorizedUsersMayNotDeleteThreads()
    {
        $this->withExceptionHandling();

        $thread = create(Thread::class);

        $this->delete($thread->path())
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);
    }
    
    /**
     * Authorized Users Can Delete Threads
     *
     * @test
     * @return void
     */
    public function authorizedUsersCanDeleteThreads()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $reply = create(Reply::class, ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id ])
            ->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());

        //Alternative to the above line is be the two assertions below
//        $this->assertDatabaseMissing('activities', [
//                'subject_id' => $thread->id,
//                'subject_type' => get_class($thread)
//            ])
//            ->assertDatabaseMissing('activities', [
//                'subject_id' => $reply->id,
//                'subject_type' => get_class($reply)
//            ]);
    }
    
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()
            ->signIn();

        $thread = make(Thread::class, $overrides);

        return $this->post(route('threads.store'), $thread->toArray());
    }
}
