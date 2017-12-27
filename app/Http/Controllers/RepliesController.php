<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Exception;
use function request;
use function resolve;
use function response;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread)
    {
        try
        {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body'    => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch ( Exception $ex )
        {
            return response(
                'Sorry, Your Reply Could Not be Saved at this time',
                422);
        }

        return $reply->load('owner');
    }


    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try{
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply->update(request(['body']));
        } catch (Exception $ex){
            return response(
                'Sorry, Your Reply Could Not be Saved at this time',
                422);
        }
    }



    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson())
        {
            return response(['status' => 'Reply Deleted']);
        }

        return back();
    }

}
