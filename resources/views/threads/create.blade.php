@extends('layouts.app')

@section('head')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create A New Thread</div>
                    
                    <div class="panel-body">
                        <form action="{{ route('threads.store') }}" method="post">
                            {{ csrf_field() }}
    
                            {{-- Channel Id --}}
                            <div class="form-group">
                                <label for="channel_id">Channel Id</label>
                                <select id="channel_id" name="channel_id" class="form-control" required>
                                    <option value="">Choose One ...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}"
                                                {{ old('channel_id') == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
    
    
                            {{-- Title --}}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input id="title" name="title" type="text" class="form-control" placeholder="Title" value="{{ old('title') }}" required>
                            </div>
    
                            {{-- Body --}}
                            <div class="form-group">
                                <label for="body">Body</label>
                                <wysiwyg name="body"></wysiwyg>
                            </div>
                            
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('council.recaptcha.key' }}"></div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"> Publish </button>
                            </div>
    
                            @if(count($errors))
                                <ul class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </form>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
