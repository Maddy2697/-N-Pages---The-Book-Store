<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        /*$books = Book::paginate(10);
        return response([
            'data' => $books,
        ],Response::HTTP_OK);*/

        return response()->json(Book::get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[

            'title' => 'required|string',
            'author' => 'required|string',
            'genre' => 'required|string',
            'description' => 'string',
            'isbn' => 'required|numeric|unique:books,isbn',
            'image' => 'required',
            'published' => 'required|date',
            'publisher' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $book = new Book;
        $image = $request->image;
        if ($image) {
            $relativePath = $this->saveImage($image);
            $image = URL::to(Storage::url($relativePath));
        }
        
        $book->title = $request->title;
        $book->author = $request->author;
        $book->genre = $request->genre;
        $book->description = $request->description;
        $book->isbn = $request->isbn;
        $book->image = $image;
        $book->published = $request->published;
        $book->publisher = $request->publisher;
        $book->save();
        /*return response([
            'data' => $book
        ],Response::HTTP_CREATED);*/
        $addData = Book::create($request->all());
        return response() -> json($addData);
    }

    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }

    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(),[

            'title' => 'required|string',
            'author' => 'required|string',
            'genre' => 'required|string',
            'description' => 'string',
            'isbn' => 'required|numeric|unique:books,isbn',
            'image' => 'required',
            'published' => 'required|date',
            'publisher' => 'required|string'
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => $validator->errors()
            ];
            return response()->json($response, 400);
        }

        $image = $request->image;
        if ($image) {
            $relativePath = $this->saveImage($image);
            $image = URL::to(Storage::url($relativePath));

            if ($book->image) {
                Storage::deleteDirectory('/public/' . dirname($book->image));
            }
        }

        $book->title = $request->title;
        $book->author = $request->author;
        $book->genre = $request->genre;
        $book->description = $request->description;
        $book->isbn = $request->isbn;
        $book->image = $image;
        $book->published = $request->published;
        $book->publisher = $request->publisher;
        $book->save();
        return response([
            'data' => $book
        ],Response::HTTP_OK);
    }

    public function show(Book $book)
    {
        return response([
            'data' => $book
        ],Response::HTTP_OK);
    }

    public function destroy(Book $b)
    {
        $b->delete();

        //return response()->noContent();
        return response()->json('success');
    }

    public function search()
    {
        $searchQuery = request('query');

        $sQuery = Book::where('title', 'like', "%{$searchQuery}%")->get();

        return response()->json($sQuery);
    }
}
