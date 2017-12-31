@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                    </h1>
                    
                    @can('update', $profileUser)
                        <form method="post" 
                              action="{{ route('avatar.store', $profileUser) }}" enctype="multipart/form-data" >
                            
                            {{ csrf_field() }}
                            <input type="file" name="avatar">
                            
                            <button type="submit" class="btn btn-primary">
                                Add Avatar
                            </button>
                        </form>
                    @endcan
    
                    <img src="{{ $profileUser->avatar() }}" width="50" height="50">
                </div>
                
                @forelse($activitiesByDate as $date => $activities)
                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach($activities as $activity)
                        @if(view()->exists("profiles.activities.{$activity->type}"))
                            @include("profiles.activities.{$activity->type}")
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet</p>
                @endforelse
{{--                {{ $threads->links() }}--}}
            </div>
        </div>
    </div>
@endsection