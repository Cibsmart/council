<!--suppress XmlDefaultAttributeValue -->
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
        {{ $reply->body }}
    </div>
    
    @can('update', $reply)
        <div class="panel-footer">
            <form action="{{ route('replies.delete', $reply) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                
                <button type="submit" class="btn btn-danger btn-xs">Delete</button>
            </form>
        </div>
    @endcan
</div>