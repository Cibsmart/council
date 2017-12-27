<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use function auth;
use function create;
use Exception;
use Illuminate\Auth\AuthenticationException;
use function make;
use function route;
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

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
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

       $this->assertDatabaseMissing('replies', [ 'id' => $reply->id]);
       $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /**
     * Unauthorized Users Cannot Update Replies
     *
     * @test
     * @return void
     */
    public function unauthorizedUsersCannotUpdateReplies()
    {
        $this->withExceptionHandling();

        $reply = create(Reply::class);

        $this->patch(route('replies.update', $reply))
            ->assertRedirect('login');

        $this->signIn()
            ->patch(route('replies.update', $reply))
            ->assertStatus(403);
    }

    /**
     * Authorized Users can Update Replies
     *
     * @test
     * @return void
     */
    public function authorizedUsersCanUpdateReplies()
    {
        $this->signIn();

        $reply = create(Reply::class, ['user_id' => auth()->id()]);

        $reply->body = $replyUpdate = 'You Have Been Changed';

        $this->patch( "replies/{$reply->id}",
            ['body' => $replyUpdate]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $replyUpdate
        ]);
    }
    
    
    /**
     * Replies that contain spam may not be create
     *
     * @test
     * @return void
     */
    public function repliesThatContainSpamMayNotBeCreate()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = create(Reply::class, [
           'body' => 'Yahoo Customer Support'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }
    
    
    /**
     * Users may only Reply a maximum of once per minute
     *
     * @test
     * @return void
     */
    public function usersMayOnlyReplyAMaximumOfOncePerMinute()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => 'My Simple Reply']);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }
}
