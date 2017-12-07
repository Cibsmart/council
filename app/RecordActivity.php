<?php
/**
 * Created by PhpStorm.
 * User: Cibsmart
 * Date: 05/12/2017
 * Time: 16:11
 */

namespace App;


use function auth;
use ReflectionClass;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if(auth()->guest()) return;
        foreach (static::getActivitiesToRecord() as $event)
        {
            static::$event(function ($model) use ($event){
                $model->recordActivity('created');
            });
        }

        static::deleting(function ($model){
           $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
//        Activity::create([
//            'type'         => $this->getActivityType($event),
//            'user_id'      => auth()->id(),
//            'subject_id'   => $this->id,
//            'subject_type' => get_class($this),
//        ]);
        //Alternatively we can use below with a polymorphic relation
        //The polymorphic relation morphMany automatically binds the subject_id & subject_type

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type'    => $this->getActivityType($event),
        ]);
    }

    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new ReflectionClass($this))->getShortName());

        return $event . '_' . $type;
    }
}