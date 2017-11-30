<?php

namespace Tests\Unit;

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class ThreadTest
 *
 * @package Tests\Unit
 */
class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $_thread;

    /**
     * Setup Method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->_thread = create(Thread::class);
    }
    
    /**
     * A Thread can make a String Path
     *
     * @test
     * @return void
     */
    public function aThreadCanMakeAStringPath()
    {
        $thread = make(Thread::class);

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path());
    }

    /**
     * A Thread Has a Creator
     *
     * @test
     * @return void
     */
    public function aThreadHasACreator()
    {
        $this->assertInstanceOf(User::class, $this->_thread->creator);
    }

    /**
     * A Thread Has Replies
     *
     * @test
     * @return void
     */
    public function AThreadHasReplies()
    {
        $this->assertInstanceOf(Collection::class, $this->_thread->replies);
    }


    /**
     * A Thread Can Add A Reply
     *
     * @test
     * @return void
     */
    public function aThreadCanAddAReply()
    {
        $this->_thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->_thread->replies);
    }
    
    /**
     * A Thread Belongs to a Channel
     *
     * @test
     * @return void
     */
    public function aThreadBelongsToAChannel()
    {
        $thread = make(Thread::class);

        $this->assertInstanceOf(Channel::class, $thread->channel);
    }
}
