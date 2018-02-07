<?php

namespace Tests\Feature;

use App\Thread;
use App\Trending;
use function create;
use Illuminate\Support\Facades\Redis;
use function json_decode;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    private $trending;

    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /**
     * It Increments A Threads Score Each time It is Read
     *
     * @test
     * @return void
     */
    public function itIncrementsAThreadsScoreEachTimeItIsRead()
    {
        $this->assertEmpty($this->trending->get());

       $thread = create(Thread::class);

       $this->call('GET', $thread->path());

       $trending = $this->trending->get();

        $this->assertCount(1, $trending);

        $this->assertEquals($thread->title, $trending[0]->title);
    }
}