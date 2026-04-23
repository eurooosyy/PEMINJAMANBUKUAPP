<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanItem;

class BooksController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();
        $hasCategoryColumn = Book::hasCategoryColumn();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publisher', 'like', '%' . $search . '%')
                    ->orWhere('isbn', 'like', '%' . $search . '%');

                if (Book::hasCategoryColumn()) {
                    $q->orWhere('category', 'like', '%' . $search . '%');
                }
            });
        }

        if ($hasCategoryColumn && $request->filled('category')) {
            $query->where('category', $request->category);
        }

        $books = $query->orderBy('title')->paginate(10)->appends($request->query());
        $categories = Book::categoryOptions();
        $categoryCounts = collect();

        if ($hasCategoryColumn) {
            $categoryCounts = Book::selectRaw('category, COUNT(*) as total')
                ->whereNotNull('category')
                ->groupBy('category')
                ->pluck('total', 'category');
        }

        return view('admin.index', compact('books', 'categories', 'categoryCounts', 'hasCategoryColumn'));
    }

    public function create()
    {
        $categories = Book::categoryOptions();
        return view('admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'stock' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string',
        ];

        if (Book::hasCategoryColumn()) {
            $rules['category'] = 'required|string|in:' . implode(',', Book::categoryOptions());
        }

        $request->validate($rules);

        $data = $request->all();
        if (!Book::hasCategoryColumn()) {
            unset($data['category']);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/books', 'public');
            $data['image'] = $imagePath;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        $equipment = $book;
        return view('admin.show', compact('book', 'equipment'));
    }

    public function edit(Book $book)
    {
        $categories = Book::categoryOptions();
        $equipment = $book;
        return view('admin.edit', compact('book', 'equipment', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'nullable|string',
        ];

        if (Book::hasCategoryColumn()) {
            $rules['category'] = 'required|string|in:' . implode(',', Book::categoryOptions());
        }

        $request->validate($rules);

        $data = $request->all();
        if (!Book::hasCategoryColumn()) {
            unset($data['category']);
        }

        if ($request->hasFile('image')) {
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $imagePath = $request->file('image')->store('images/books', 'public');
            $data['image'] = $imagePath;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->image && Storage::disk('public')->exists($book->image)) {
            Storage::disk('public')->delete($book->image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    public function catalog(Request $request)
    {
        $query = Book::query();
        $hasCategoryColumn = Book::hasCategoryColumn();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publisher', 'like', '%' . $search . '%');

                if (Book::hasCategoryColumn()) {
                    $q->orWhere('category', 'like', '%' . $search . '%');
                }
            });
        }

        $selectedCategory = $request->input('category', $request->input('kategori'));
        if ($hasCategoryColumn && !empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        if ($request->has('available') && $request->available == '1') {
            $query->where('stock', '>', 0);
        }

        $books = $query->orderBy('title')->paginate(12)->appends($request->query());
        $categories = Book::categoryOptions();

        return view('catalog', compact('books', 'categories', 'hasCategoryColumn'));
    }
}
