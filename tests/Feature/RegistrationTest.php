<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A Confirmation Email is Sent Upon Registration
     *
     * @test
     * @return void
     */
    public function aConfirmationEmailIsSentUponRegistration()
    {
        Mail::fake();

        event(new Registered(create(User::class)));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /**
     * User Can Fully Confirm Their Email Addresses
     *
     * @test
     * @return void
     */
    public function userCanFullyConfirmTheirEmailAddresses()
    {
        $this->post('/register', [
           'name'                  => 'John',
           'email'                 => 'john@example.com',
           'password'              => 'foobar',
           'password_confirmation' => 'foobar'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $res = $this->get(route('confirmation.index', ['token' => $user->confirmation_token]));

        $this->assertTrue($user->fresh()->confirmed);

        $res->assertRedirect(route('threads.index'));
    }
}
