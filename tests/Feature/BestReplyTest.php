<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BestReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A Thread Creator may mark any reply as the Best Reply
     *
     * @test
     * @return void
     */
    public function aThreadCreatorMayMarkAnyReplyAsTheBestReply()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->assertFalse($replies[1]->fresh()->isBest());

        $this->postJson(route('best-replies.store', $replies[1]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }
    
    /**
     * Only the Thread Creator may mark a reply as Best
     *
     * @test
     * @return void
     */
    public function onlyTheThreadCreatorMayMarkAReplyAsBest()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $replies = create(Reply::class, ['thread_id' => $thread->id], 2);

        $this->signIn(create(User::class));

        $this->postJson(route('best-replies.store', $replies[1]))->assertStatus(403
        );

        $this->assertFalse($replies[1]->fresh()->isBest());

    }
}