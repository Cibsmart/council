<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use function create;
use function make;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    
    
    /**
     * Mentioned Users in A Reply Are Notified
     *
     * @test
     * @return void
     */
    public function mentionedUsersInAReplyAreNotified()
    {
        $john = create(User::class, ['name' => 'JohnDoe']);

        $this->signIn($john);

        $jane = create(User::class, ['name' => 'JaneDoe']);

        $thread = create(Thread::class);

        $reply = make(Reply::class, ['body' => '@JaneDoe Look at this, @Cib']);

        $this->postJson($thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $jane->notifications);
    }

    
    /**
     * It can fetch all mentioned Users starting with the given characters
     *
     * @test
     * @return void
     */
    public function itCanFetchAllMentionedUsersStartingWithTheGivenCharacters()
    {
        create(User::class, ['name' => 'JohnDoe']);
        create(User::class, ['name' => 'JohnDoe2']);
        create(User::class, ['name' => 'JaneDoe']);

        $result = $this->json('GET','/api/users', ['name' => 'Joh']);

        $this->assertCount(2, $result->json());
    }
}