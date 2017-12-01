@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">
                            {{ $thread->creator->name }}
                        </a>
                        posted
                        {{ $thread->title }}
                    </div>
                    
                    <div class="panel-body">
                        {{ $thread->body }}
                    </div>
                </div>
                
                {{--Replies--}}
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach
                
                {{ $replies->links() }}
                
                {{--Add New Reply--}}
                @if(auth()->check())
                    <form action="{{ $thread->path() . '/replies' }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have Something to Say" rows="5"></textarea>
                        </div>
                        <button class="btn btn-default">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{ route('login') }}">Sign in</a> to participate in this discussion</p>
                @endif
            </div>
            
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>
                            This thread was published
                            {{ $thread->created_at->diffForHumans() }} by
                            <a href="#">{{ $thread->creator->name }}</a>, currently has {{ $thread->replies_count }}
                            {{ str_plural('comment', $thread->replies_count) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    
    </div>
@endsection
