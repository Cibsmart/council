<?php

namespace Tests\Feature;

use App\Thread;
use function config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A User Can Search Threads
     *
     * @test
     * @return void
     */
    public function aUserCanSearchThreads()
    {
        config(['scout.driver' => 'algolia']);

        $search = 'foobar';

        create(Thread::class, [], 2);

        create(Thread::class, ['body' => "A thread with the {$search} term"], 2);

        do
        {
            sleep(.25);

            $results = $this->getJson("/threads/search?q={$search}")->json()['data'];
        }while (empty($results));

        $this->assertCount(2, $results);

        Thread::latest()->take(4)->unsearchable();
    }
}
