<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        if (request()->expectsJson()) {
            return Thread::search(request('q'))->paginate(25);
        }

        $trending = $trending->get();

        return view('threads.search', compact('trending'));
    }
}
