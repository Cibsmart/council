<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Favourite
 *
 * @mixin \Eloquent
 * @property \Carbon\Carbon $created_at
 * @property int            $id
 * @property \Carbon\Carbon $updated_at
 */
class Favourite extends Model
{
    use RecordActivity;

   protected $guarded = [];

    public function favourited()
    {
        return $this->morphTo();
   }
}
