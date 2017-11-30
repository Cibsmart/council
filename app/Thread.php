<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed          $creator
 * @property mixed          $replies
 * @property int            $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */

class Thread extends Model
{
    protected $guarded = [];

    public function path()
    {
        return '/threads/' . $this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }
}
