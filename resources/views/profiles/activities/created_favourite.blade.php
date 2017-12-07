@component('profiles.activities.activity')
    @slot('heading')
        <span class="glyphicon glyphicon-heart"></span>
    
        <a href="{{ $activity->subject->favourited->path() }}">
            {{ $profileUser->name }} favourited a reply
        </a>
    @endslot
    
    @slot('body')
        {{ $activity->subject->favourited->body }}
    @endslot
@endcomponent