<?php

namespace Tests\Feature;

use App\Inspections\Spam;
use Exception;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * it Checks For Invalid Keywords
     *
     * @test
     * @return void
     */
    public function itChecksForInvalidKeywords()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent Reply Here'));

        $this->expectException('Exception');

        $spam->detect('yahoo customer support');
    }

    /**
     * It Checks for any key being held down
     *
     * @test
     * @return void
     */
    public function itChecksForAnyKeyBeingHeldDown()
    {
        $spam = new Spam();

        $this->expectException(Exception::class);

        $spam->detect('Hello world aaaaaaaaaaaaaaaaa');
    }
}
