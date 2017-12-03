<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Reply
 *
 * @property \Carbon\Carbon $created_at
 * @property int            $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed          $owner
 * @property int $thread_id
 * @property int $user_id
 * @property string $body
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereThreadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Reply whereUserId($value)
 * @mixin \Eloquent
 */

class Reply extends Model
{
    use Favouritable;

    protected $guarded = [];

    protected $with = ['owner', 'favourites'];

    public function path()
    {
        return route('favourites.store', $this->id);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
