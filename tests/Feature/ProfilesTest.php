<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use function auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A User Has a Profile
     *
     * @test
     * @return void
     */
    public function aUserHasAProfile()
    {
        $user = create(User::class);

        $this->get(route('profiles.show', $user->name))
            ->assertSee($user->name);
    }
    
    /**
     * Profiles Display all Threads Created by the Associated User
     *
     * @test
     * @return void
     */
    public function profilesDisplayAllThreadsCreatedByTheAssociatedUser()
    {
        $this->signIn();

        $thread = create(Thread::class, ['user_id' => auth()->id()]);

        $this->get(route('profiles.show', auth()->user()->name ))
            ->assertSee($thread->title)
            ->assertSee(($thread->body));
    }
}