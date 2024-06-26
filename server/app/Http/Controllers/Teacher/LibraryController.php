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
                'search' => 'required|string|max:255'
            ]);

            $books = Book::query();

            $books->orWhere('author', 'like', '%' . $validated['search'] . '%');
            $books->orWhere('class', 'like', '%' . $validated['search'] . '%');
            $books->orWhere('title', 'like', '%' . $validated['search'] . '%');

            return response()->json($books->get());
        }
    }
