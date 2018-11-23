<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function preg_replace;
use Stevebauman\Purify\Facades\Purify;

/**
 * App\Reply
 *
 * @property \Carbon\Carbon $created_at
 * @property int            $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed          $owner
 * @property int            $thread_id
 * @property int            $user_id
 * @property string         $body
 * @property mixed          $thread
 * @property mixed          $attributes
 * @property bool           $is_best
 * @method static Builder|\App\Reply whereBody($value)
 * @method static Builder|\App\Reply whereCreatedAt($value)
 * @method static Builder|\App\Reply whereId($value)
 * @method static Builder|\App\Reply whereThreadId($value)
 * @method static Builder|\App\Reply whereUpdatedAt($value)
 * @method static Builder|\App\Reply whereUserId($value)
 * @mixin \Eloquent
 */

class Reply extends Model
{
    use Favouritable, RecordActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    protected $appends = ['favouritesCount', 'isFavourited', 'isBest'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply){
           $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply){
//            if($reply->isBest()){
//                $reply->thread->update(['best_reply_id' => null]);
//            }
           $reply->thread->decrement('replies_count');
        });
    }


    public function path()
    {
//        return route('favourites.store', $this->id);
        return $this->thread->path() . "#reply-{$this->id}";
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }


    public function mentionedUsers()
    {
        preg_match_all('/\@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }


    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace(
            '/@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>',
            $body);
    }


    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }

    public function getBodyAttribute($body)
    {
        return Purify::clean($body);
    }
}
