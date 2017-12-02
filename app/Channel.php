<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Channel
 *
 * @property \Carbon\Carbon $created_at
 * @property int            $id
 * @property \Carbon\Carbon $updated_at
 * @property mixed          $threads
 * @property string $name
 * @property string $slug
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Channel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Channel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Channel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Channel whereUpdatedAt($value)
 * @mixin \Eloquent
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
