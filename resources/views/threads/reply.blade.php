<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="panel panel-default">
        <div class="panel-heading">
            <div class="level">
                
                <h5 class="flex">
                    <a href="{{ route('profiles.show', $reply->owner) }}"> {{ $reply->owner->name }} </a>
                    said
                    {{ $reply->created_at->diffforhumans() }} ...
                </h5>
                
                <div>
                    <form action="{{ route('favourites.store', $reply) }}" method="post">
                        {{ csrf_field() }}
                        
                        <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                            {{ $reply->favourites_count }} {{ str_plural('favourite', $reply->favourites_count) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                
                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            
            <div v-else v-text="body"></div>
        </div>
        
        @can('update', $reply)
            <div class="panel-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                
                <form action="{{ route('replies.delete', $reply) }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('delete') }}
                    
                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form>
            </div>
        @endcan
    </div>
</reply>