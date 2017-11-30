@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create A New Thread</div>
                    
                    <div class="panel-body">
                        <form action="{{ route('threads.store') }}" method="post">
                            {{ csrf_field() }}
    
                            {{-- Title --}}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" name="title" type="text" class="form-control" placeholder="Title">
                            </div>
    
                            {{-- Body --}}
                            <div class="form-group">
                                <label for="body">Body</label>
                                <textarea id="body" name="body" class="form-control" placeholder="Body" rows="8" style="overflow:hidden"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Publish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection