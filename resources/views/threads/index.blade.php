@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads._list')
                
                {{ $threads->render() }}
            </div>
            
            {{--Search--}}
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Search
                    </div>
        
                    <div class="panel-body">
                        <form action="{{ route('search.show') }}" method="get">
                            <div class="form-group">
                                <input name="q" class="form-control" type="text" placeholder="Search for something">
                            </div>
                            
                            <div class="form-group">
                                <button class="btn btn-default" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                {{--Trending--}}
                @if(count($trending))
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Trending Threads
                        </div>
                        
                        <div class="panel-body">
                            @foreach($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">{{  $thread->title }} </a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
