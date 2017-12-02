<div class="panel panel-default">
    <div class="panel-heading">
        <div class="level">
            
            <h5 class="flex">
                <a href="#"> {{ $reply->owner->name }} </a>
                said
                {{ $reply->created_at->diffforhumans() }} ...
            </h5>
            
            <div>
                <form action="{{ route('favourites.store', $reply) }}" method="post">
                    {{ csrf_field() }}
                    
                    <button type="submit" class="btn btn-default" {{ $reply->isFavourited() ? 'disabled' : '' }}>
                        {{ $reply->favourites()->count() }} {{ str_plural('favourite', $reply->favourites()->count()) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-body">
        {{ $reply->body }}
    </div>
</div>