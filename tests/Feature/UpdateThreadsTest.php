<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->signIn()
            ->withExceptionHandling();
    }


    /**
     * A Thread Requires a Title and Body to be Updated
     *
     * @test
     * @return void
     */
    public function aThreadRequiresATitleAndBodyToBeUpdated()
    {

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
        ])->assertSessionHasErrors('body');

        $this->patch($thread->path(), [
            'body' => 'Changed Body',
        ])->assertSessionHasErrors('title');
    }


    /**
     * Unauthorized User Cannot Update Thread
     *
     * @test
     * @return void
     */
    public function unauthorizedUserCannotUpdateThread()
    {
        $thread = create(Thread::class, ['user_id' => create(User::class)->id]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body'  => 'Changed Body',
        ]);

        $this->patch($thread->path(), [])
            ->assertStatus(403);
    }


    /**
     * A Thread Can be Updated By Its Creator
     *
     * @test
     * @return void
     */
    public function aThreadCanBeUpdatedByItsCreator()
    {
        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'Changed',
            'body'  => 'Changed Body',
        ]);

        tap($thread->fresh(), function ($thread) {
            $this->assertEquals('Changed', $thread->title);
            $this->assertEquals('Changed Body', $thread->body);
        });
    }
}