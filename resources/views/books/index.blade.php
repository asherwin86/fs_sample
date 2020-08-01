@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
        @if(session()->get('success'))
            <div class="alert alert-success">
            {{ session()->get('success') }}  
            </div>
        @endif
        </div>
        <div class="col-sm-11">
            <h1>Books</h1>
        </div>
        <div class="col-sm-1">
            <a href="{{ route('books.create') }}" class="btn btn-primary mt-2">New</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>Title</td>
                        <td>Author</td>
                        <td>Blurb</td>
                        <td>Status</td>
                        <td>Actions</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                    <tr>
                        <td class="text-capitalize">{{$book->title}}</td>
                        <td class="text-capitalize">{{$book->author}}</td>
                        <td class="text-capitalize">{{$book->blurb}}</td>
                        <td class="text-capitalize">{{$book->status}}</td>
                        <td>
                            <div class="btn-toolbar" role="toolbar">
                            @can('update-book', $book)
                                <div class="btn-group mr-2" role="group">
                                    <a href="{{ route('books.edit',$book->id)}}" class="btn btn-primary">Edit</a>
                                </div>
                            @endcan
                            @can('destroy-book', $book)
                                <div class="btn-group mr-2" role="group">
                                    <form action="{{ route('books.destroy', $book->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Delete</button>
                                    </form>
                                </div>
                            @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $books->links() }}
    </div>
</div>
@endsection