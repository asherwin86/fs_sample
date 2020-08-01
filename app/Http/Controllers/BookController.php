<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\Book as BookResource;

class BookController extends Controller
{
    // TODO: Make an area where someone can add books they've bought
    /*
     * Books need a title, author, blurb, status (not started, started, finished, retired). 
     * Need a list of current books paginated, and a way to add/edit/remove books
     */

    // TODO: Make an api route where someone can get their books (needs to be filtered by current status only)

    // TODO; Make an api route where someone can get the details of 1 book by id
    /*
     *
     * https://laravel.com/docs/6.x/api-authentication - use the token guard here
     */

    /**
     * The home page.
     * List all the books that is associated to the logged in user only.
     * 
     */
    public function index()
    {
        $books = Book::where('user_id', Auth::id())->paginate(25);

        return view('books.index', compact('books'));
    }

    /** 
     * View for creating a book.
    */
    public function create()
    {
        return view('books.create', ['statuses' => Book::STATUSES]);
    }

    /**
     * API for searching the book results based off the status. Currently only shows books based on current user.
     * @param $status string One of the valid statuses defined in Book::STATUSES.
     */
    public function search($status)
    {
        $books = Book::where('status', $status)->where('user_id', Auth::id())->get();

        return BookResource::collection($books);
    }

    /**
     * API for showing a book of the current user.
     * @param $id string The id of the book.
     */
    public function view($id)
    {
        $book = Book::findOrFail($id);
        if (Gate::denies('view-book', $book)) {
            abort(403);
        }
        return new BookResource($book);
    }

    /**
     * View for the editing of a book. Only allowed if the current user was the one who added the book.
     * @param $id string The id of the book.
     */
    public function edit($id)
    {
        $book = Book::find($id);
        if (Gate::denies('update-book', $book)) {
            abort(403);
        }
        return view('books.edit', compact('book'));
    }

    /**
     * Update a book.
     * User must be owner of the book to edit it.
     * @param $request Request The laravel Request object.
     * @param $id string The id of the book.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'blurb' => 'required',
            'status' => [ 'required',  Rule::in(Book::STATUSES)],
        ]);

        $book = Book::find($id);

        if (Gate::denies('update-book', $book)) {
            abort(403);
        }

        $book->title = $request->get('title');
        $book->author = $request->get('author');
        $book->blurb = $request->get('blurb');
        $book->status = $request->get('status');
        $book->save();

        return redirect('/books')->with('success', __('Book updated.'));
    }

    /**
     * Destroy a book.
     * Must be owner of the book to destroy it.
     * @param $id string the id of the book.
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        
        if (Gate::denies('destroy-book', $book)) {
            abort(403);
        }

        $book->delete();

        return redirect('/books')->with('success', __('Book deleted.'));
    }

    /**
     * Endpoint for creating a book.
     * @param $request Request Laravel request obeject.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'blurb' => 'required',
            'status' => [ 'required',  Rule::in(Book::STATUSES)],
        ]);

        $book = new Book([
            'user_id' => Auth::id(),
            'title' => $request->get('title'),
            'author' => $request->get('author'),
            'blurb' => $request->get('blurb'),
            'status' => $request->get('status'),
        ]);
        $book->save();

        return redirect('/books')->with('success', __('Book saved.'));
    }
}
