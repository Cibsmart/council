<?php

namespace Tests\Feature;

use App\Reply;
use Exception;
use function route;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavouritesTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Guest cannot favourite anything
     *
     * @test
     * @return void
     */
    public function guestCannotFavouriteAnything()
    {
        $this->withExceptionHandling()
            ->post(route('favourites.store', 1))
            ->assertRedirect('login');
    }
    /**
     * An Authenticated User can Favourite Any Reply
     *
     * @test
     * @return void
     */
    public function anAuthenticatedUserCanFavouriteAnyReply()
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->post(route('favourites.store', $reply));

        $this->assertCount(1, $reply->favourites);
    }

    /**
     * An Authenticated User May Only Favourite a Reply Once
     *
     * @test
     * @return void
     */
    public function anAuthenticatedUserMayOnlyFavouriteAReplyOnce()
    {
        $this->signIn();

        $reply = create(Reply::class);

        try{
            $this->post(route('favourites.store', $reply));

            $this->post(route('favourites.store', $reply));
        } catch (Exception $ex){
            $this->fail('Did not expect to insert the same record set twice');
        }

        $this->assertCount(1, $reply->favourites);
    }
}