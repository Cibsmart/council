<?php

namespace App\Filters;


use function collect;
use Illuminate\Http\Request;
use function method_exists;

abstract class Filters
{

    /**
     * @var Request
     */
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        $this->getFilters()
            ->filter(function ($filter){
                return method_exists($this, $filter);
            })
            ->each(function($filter, $value){
               $this->$filter($value);
            });

        //Alternative to the above functional approach is below
//        foreach ($this->getFilters() as $value => $filter)
//        {
//            if( method_exists($this, $filter)){
//                $this->$filter($value);
//            }
//        }

        return $this->builder;

    }

    public function getFilters()
    {
        //collect() turns the array into a collection with (value, key) and flip (key, value)
        return collect($this->request->intersect($this->filters))->flip();
    }
}