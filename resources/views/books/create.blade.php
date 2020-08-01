@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <h1>{{ __("Add a Book") }}</h1>
    <div>
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div>
    @endif
      <form method="post" action="{{ route('books.store') }}">
          @csrf
          <div class="form-group">    
              <label for="title">{{ __("Title") }}</label>
              <input type="text" class="form-control" name="title"/>
          </div>

          <div class="form-group">
              <label for="last_name">{{ __("Author") }}</label>
              <input type="text" class="form-control" name="author"/>
          </div>

          <div class="form-group">
              <label for="blurb">{{ __("Blurb") }}</label>
              <input type="text" class="form-control" name="blurb"/>
          </div>
          <div class="form-group">
              <label for="status">{{ __("Status") }}</label>
              <select class="form-control" name="status">
                    @foreach($statuses as $status)
                    <option value="{{$status}}">{{ __(ucwords($status)) }}</option>
                    @endforeach
              </select>
          </div>
          <button type="submit" class="btn btn-primary">{{ __("Add") }}</button>
      </form>
  </div>
</div>
</div>
@endsection