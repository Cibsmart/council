<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use function auth;
use function create;
use function route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    
    /**
     * A Notification is Prepared When a Subscribed Thread Receives a New Reply
     * That is Not By the Current User
     *
     * @test
     * @return void
     */
    public function aNotificationIsPreparedWhenASubscribedThreadReceivesANewReplyThatIsNotByTheCurrentUser()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some Reply Here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(Thread::class)->id,
            'body' => 'Some Reply Here'
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
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some Reply Here'
        ]);

        $user = auth()->user();

        $response =$this->getJson(route('notifications.index', [$user->name]))->json();

        $this->assertCount(1, $response);

    }


    /**
     * A user Can Mark a Notification as Read
     *
     * @test
     * @return void
     */
    public function aUserCanMarkANotificationAsRead()
    {
        $this->signIn();

        $thread = create(Thread::class)->subscribe();

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body' => 'Some Reply Here'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unReadNotifications);

        $notificationId = $user->unReadNotifications->first()->id;

        $this->delete(route('notifications.destroy', [$user->name, $notificationId]));

        $this->assertCount(0, $user->fresh()->unReadNotifications);

    }
}