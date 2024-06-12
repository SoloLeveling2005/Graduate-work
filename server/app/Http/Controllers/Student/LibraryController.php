<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class LibraryController extends Controller
{
    public function index()
    {
        $books = Book::all()->groupBy('subject');
        return response()->json($books);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'search' => 'required|string|max:255'
        ]);

        $books = Book::query();

        $books->orWhere('author', 'like', '%' . $validated['search'] . '%');
        $books->orWhere('class', 'like', '%' . $validated['search'] . '%');
        $books->orWhere('title', 'like', '%' . $validated['search'] . '%');

        return response()->json($books->get());
    }
}
