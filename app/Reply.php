<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    protected $appends = ['favouritesCount', 'isFavourited'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply){
           $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply){
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

}
