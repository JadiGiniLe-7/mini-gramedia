<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $subscriptionPackages = SubscriptionPackage::latest()->take(3)->get();
        $latestBooks = Book::orderBy('created_at', 'desc')->limit(10)->get();
        $freeBooks = Book::where('price', 0)->orderBy('created_at', 'desc')->limit(6)->get();

        return view('home', compact('subscriptionPackages', 'latestBooks', 'freeBooks'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category');
        $books = Book::query();
        $kategori = BookCategory::find($categoryId);
        if ($query) {
            $books->where(function ($buku) use ($query) {
                $buku->where('title', 'like', '%' . $query . '%')
                    ->orWhere('writer', 'like', '%' . $query . '%');
            });
        }
        if ($categoryId) {
            $books->where('book_category_id', $categoryId);
        }

        $books = $books->get();
        return view('search_result', compact('books', 'query', 'kategori'));
    }
}
