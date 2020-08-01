@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1>{{ __("Update a Book") }}</h1>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ __("$error") }}</li>
                @endforeach
            </ul>
        </div>
        <br /> 
        @endif
        <form method="post" action="{{ route('books.update', $book->id) }}">
            @method('PATCH') 
            @csrf
            <div class="form-group">
                <label for="title">{{ __("Title") }}</label>
                <input type="text" class="form-control" name="title" value="{{ $book->title }}" />
            </div>

            <div class="form-group">
                <label for="author">{{ __("Author") }}</label>
                <input type="text" class="form-control" name="author" value="{{ $book->author }}" />
            </div>

            <div class="form-group">
                <label for="blurb">{{ __("Blurb") }}</label>
                <input type="text" class="form-control" name="blurb" value="{{ $book->blurb }}" />
            </div>
            <div class="form-group">
                <label for="status">{{ __("Status") }}</label>
                <select class="form-control" name="status">
                    @foreach($book::STATUSES as $status)
                    <option value="{{$status}}"
                    @if ($book->status == $status)
                        selected="selected"
                    @endif
                    >{{ __(ucwords($status)) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">{{ __("Update") }}</button>
        </form>
    </div>
</div>
@endsection