<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class LibraryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:books,title',
            'author' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,txt',
        ]);

        // Сохранение файла книги в папку library
        $filePath = $request->file('file')->store('library', 'public');

        // Создание записи книги
        $book = Book::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'class' => $validated['class'],
            'subject' => $validated['subject'],
            'file_path' => $filePath,
        ]);

        return response()->json($book, 201);
    }

    public function index()
    {
        $books = Book::all()->groupBy('subject');
        return response()->json($books);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'author' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $books = Book::query();

        if (isset($validated['author'])) {
            $books->where('author', 'like', '%' . $validated['author'] . '%');
        }
        if (isset($validated['class'])) {
            $books->where('class', 'like', '%' . $validated['class'] . '%');
        }
        if (isset($validated['title'])) {
            $books->where('title', 'like', '%' . $validated['title'] . '%');
        }

        return response()->json($books->get());
    }
}
