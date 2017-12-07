<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use function auth;
use function create;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Unauthenticated Users May Not Add Replies
     *
     * @test
     * @return void
     */
    public function unauthenticatedUsersMayNotAddReplies()
    {
        $thread = create(Thread::class);

        $reply = make(Reply::class);

        $this->withExceptionHandling()
            ->post($thread->path() . '/replies', $reply->toArray())
            ->assertRedirect('login');
    }
    
    /**
     * An Authenticated User May Participate In Forum Thread
     *
     * @test
     * @return void
     */
    public function anAuthenticatedUserMayParticipateInForumThread()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class);

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    
    /**
     * A Reply Requires a Body
     *
     * @test
     * @return void
     */
    public function aReplyRequiresABody()
    {
       $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /**
     * Unauthorized Users Cannot Delete Replies
     *
     * @test
     * @return void
     */
    public function unauthorizedUsersCannotDeleteReplies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->delete(route('replies.delete', $reply))
            ->assertRedirect('login');

        $this->signIn()
            ->delete(route('replies.delete', $reply))
            ->assertStatus(403);
    }
    
    /**
     * Authorized User can Delete Replies
     *
     * @test
     * @return void
     */
    public function authorizedUserCanDeleteReplies()
    {
       $this->signIn();

       $reply = create(Reply::class, ['user_id' => auth()->id()]);

       $this->delete(route('replies.delete', $reply))
           ->assertStatus(302);

       $this->assertDatabaseMissing('replies', $reply->toArray());

    }
}
