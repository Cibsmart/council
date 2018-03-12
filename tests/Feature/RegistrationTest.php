<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
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

        $this->post(route('register'), [
            'name'                  => 'John',
            'email'                 => 'john@example.com',
            'password'              => 'foobar',
            'password_confirmation' => 'foobar'
        ]);

        Mail::assertQueued(PleaseConfirmYourEmail::class);
    }

    /**
     * User Can Fully Confirm Their Email Addresses
     *
     * @test
     * @return void
     */
    public function userCanFullyConfirmTheirEmailAddresses()
    {
        Mail::fake();

        $this->post(route('register'), [
           'name'                  => 'John',
           'email'                 => 'john@example.com',
           'password'              => 'foobar',
           'password_confirmation' => 'foobar'
        ]);

        $user = User::whereName('John')->first();

        $this->assertFalse($user->confirmed);

        $this->assertNotNull($user->confirmation_token);

        $this->get(route('confirmation.index', ['token' => $user->confirmation_token]))
            ->assertRedirect(route('threads.index'));

        tap($user->fresh(), function ($user){
            $this->assertTrue($user->confirmed);
            $this->assertNull($user->confirmation_token);
        });

    }

    /**
     * Confirming an Invalid Token
     *
     * @test
     * @return void
     */
    public function confirmingAnInvalidToken()
    {
        $this->get(route('confirmation.index', ['token' => 'invalid']))
           ->assertRedirect(route('threads.index'))
           ->assertSessionHas('flash', 'Unknown Token');
    }
}
