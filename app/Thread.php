<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use function auth;
use function cache;
use Illuminate\Database\Eloquent\Model;
use function sprintf;

/**
 * App\Thread
 *
 * @property mixed          $creator
 * @property mixed          $replies
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed          $channel
 * @property int            $reply_count
 * @property int            $user_id
 * @property int            $channel_id
 * @property string         $title
 * @property string         $body
 * @property mixed          $subscriptions
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread filter($filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Thread whereUserId($value)
 * @mixin \Eloquent
 */
class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel' ];

    protected $appends = ['isSubscribedTo'];

    protected static function boot()
    {
        parent::boot();

        //Added a replies_count on the thread table
//        static::addGlobalScope('replyCount', function ($builder) {
//            $builder->withCount('replies');
//        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();

            //Alternative to above using higher order messaging
//            $thread->replies->each(function ($reply){
//               $reply->delete();
//            });
        });
    }


    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }


    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }


    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

//        event(new ThreadHasNewReply($this, $reply));
        $this->notifySubscribers($reply);

        return $reply;
    }


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }


    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }


    public function unSubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }


    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
    

    /**
     * @param $reply
     */
    public function notifySubscribers($reply)
    {
        $this->subscriptions
            ->where('user_id', '!=', $reply->user_id)
            ->each
            ->notify($reply);
    }


    public function hasUpdatesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }
}
