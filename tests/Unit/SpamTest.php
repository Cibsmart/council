<?php

namespace Tests\Feature;

use App\Spam;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SpamTest extends TestCase
{
    /**
     * It Validates Spam
     *
     * @test
     * @return void
     */
    public function itValidatesSpam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent Reply Here'));
    }
    
}