<?php

namespace Tests\Feature;

use App\Thread;
use function create;
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
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => Thread::class
        ];

        $this->assertDatabaseHas('activities', $activity);
    }
}