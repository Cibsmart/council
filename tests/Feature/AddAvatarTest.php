<?php

namespace Tests\Feature;

use function auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;


    /**
     * Only Members Can add Avatars
     *
     * @test
     * @return void
     */
    public function onlyMembersCanAddAvatars()
    {
        $this->withExceptionHandling();

       $this->json('POST', '/api/users/1/avatar')
           ->assertStatus(401);

    }
    
    
    /**
     * A Valid Avatar must be provided
     *
     * @test
     * @return void
     */
    public function aValidAvatarMustBeProvided()
    {
        $this->withExceptionHandling()
            ->signIn();

        $this->json('POST', '/api/users/' . auth()->id() . '/avatar',[
            'avatar' => 'not-an-image'
        ])->assertStatus(422);

    }
    
    
    /**
     * A User May Add An Avatar to their Profile
     *
     * @test
     * @return void
     */
    public function aUserMayAddAnAvatarToTheirProfile()
    {
        $this->signIn();

        Storage::fake('public');

        $this->json('POST', '/api/users/' . auth()->id() . '/avatar',[
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals('/storage/avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')
            ->assertExists('avatars/' . $file->hashName());
    }
}