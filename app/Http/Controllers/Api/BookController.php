<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller{
            // List all books
            public function index()
     {

        return response()->json(Book::all());
}

       // Add a new book
            public function store(Request $request)
        {
            $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'year' => $request->year,
    ]);

     return response()->json([
     'message' => 'Book added successfully',
     'book' => $book
]);
}

    // Retrieve a specific book by ID
        public function show($id)
    {
        $book = Book::find($id);
        if ($book) {
            return response()->json($book, 200);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }

    // Update a specific book by ID
        public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if ($book){
            $validatedData = $request->validate([
                'title' => 'sometimes|required|string',
                'author' => 'sometimes|required|string',
                'year' => 'sometimes|required|integer',
            ]);
            $book->update($validatedData);
            return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
        }
    
    // Delete a specific book by ID
        public function destroy($id)
    {
        $book = Book::find($id);
        if ($book) {
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }

}