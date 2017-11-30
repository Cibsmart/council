<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property \Carbon\Carbon $created_at
 * @property int            $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed          $threads
 */
class Channel extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    //
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
