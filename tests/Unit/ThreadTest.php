<?php

namespace Tests\Unit;

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
}
