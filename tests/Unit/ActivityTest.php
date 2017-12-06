<?php

namespace Tests\Feature;

use App\Activity;
use App\Reply;
use App\Thread;
use function auth;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * It Records Activity When a Thread is Created
     *
     * @test
     * @return void
     */
    public function itRecordsActivityWhenAThreadIsCreated()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $activity = [
            'type'         => 'created_thread',
            'user_id'      => auth()->id(),
            'subject_id'   => $thread->id,
            'subject_type' => Thread::class
        ];

        $this->assertDatabaseHas('activities', $activity);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }
    
    /**
     * It Records Activity When A Reply is Created
     *
     * @test
     * @return void
     */
    public function itRecordsActivityWhenAReplyIsCreated()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->assertEquals(2, Activity::count());
    }
    
    /**
     * Fetches A Feed for any User
     *
     * @test
     * @return void
     */
    public function fetchesAFeedForAnyUser()
    {
        $this->signIn();

        create(Thread::class, ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);

        $feed = Activity::feed(auth()->user(), 50);

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}
