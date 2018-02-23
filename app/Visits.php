<?php
/**
 * Created by PhpStorm.
 * User: Cibsmart
 * Date: 23/02/2018
 * Time: 08:58
 */

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }


    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }


    public function record()
    {
        Redis::incr("threads.{$this->thread->id}.visits");

        return $this;
    }


    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }


    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}