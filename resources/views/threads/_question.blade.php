{{--Editing--}}
<div class="panel panel-default" v-if="editing">
    <div class="panel-heading">
        <div class="level">
            <input class="form-control" type="text" value="{{ $thread->title }}" >

        </div>
    </div>
    
    <div class="panel-body">
        <div class="form-group">
            <textarea class="form-control"  rows="10">
                {{ $thread->body }}
            </textarea>
        </div>
    </div>
    
    <div class="panel-footer">
        <div class="level">
            <button class="btn btn-xs btn-primary level-item" @click="">Update</button>
            <button class="btn btn-xs level-item" @click="editing = false">Cancel</button>
        
            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="post" class="ml-a">
                    {{ csrf_field() }} {{ method_field('DELETE') }}
                
                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            @endcan
        </div>
    </div>
</div>


{{--Not Editing--}}
<div class="panel panel-default" v-else>
    <div class="panel-heading">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" class="mr-1" width="25" height="25">
            <span class="flex">
                <a href="{{ route('profiles.show', $thread->creator) }}">
                    {{ $thread->creator->name }}
                </a>
                posted {{ $thread->title }}
            </span>

        </div>
    </div>
    
    <div class="panel-body">
        {{ $thread->body }}
    </div>
    
    <div class="panel-footer">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>