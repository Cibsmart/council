<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A Channel Consists of Threads
     *
     * @test
     * @return void
     */
    public function aChannelConsistsOfThreads()
    {
        $channel = create(Channel::class);

        $thread = create(Thread::class, ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}