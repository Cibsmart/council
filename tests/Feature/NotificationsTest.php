<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /**
     * A Notification is Prepared When a Subscribed Thread Receives a New Reply
     * That is Not By the Current User
     *
     * @test
     * @return void
     */
    public function aNotificationIsPreparedWhenASubscribedThreadReceivesANewReplyThatIsNotByTheCurrentUser()
    {
        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some Reply Here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(Thread::class)->id,
            'body'    => 'Some Reply Here',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * A User Can Fetch Their Unread Notifications
     *
     * @test
     * @return void
     */
    public function aUserCanFetchTheirUnreadNotifications()
    {
        create(DatabaseNotification::class);

        $this->assertCount(
            1,
            $this->getJson(route('notifications.index', [auth()->user()->name]))->json()
        );
    }

    /**
     * A user Can Mark a Notification as Read
     *
     * @test
     * @return void
     */
    public function aUserCanMarkANotificationAsRead()
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unReadNotifications);

            $this->delete(route('notifications.destroy', [$user->name, $user->unReadNotifications->first()->id]));

            $this->assertCount(0, $user->fresh()->unReadNotifications);
        });
    }
}
