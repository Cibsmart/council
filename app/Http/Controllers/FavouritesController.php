<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Reply;
use function auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function response;

class FavouritesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reply $reply
     */
    public function store(Reply $reply)
    {
        $reply->favourite();

        return back();
    }

    public function destroy(Reply $reply)
    {
        $reply->unfavourite();

//        return response([], 200);
    }
}
