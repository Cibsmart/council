@component('profiles.activities.activity')
    @slot('heading')
        <span class="glyphicon glyphicon-blackboard"></span>
    
        {{ $profileUser->name }} published
        <a href="{{ $activity->subject->path() }}">
            {{ $activity->subject->title }}
        </a>
    @endslot
    
    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent