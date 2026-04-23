<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Book as Equipment; // Temporary alias to avoid breaking sidebar/routes
use App\Models\Loan;
use App\Models\LoanItem;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::paginate(10);
        $categories = Book::categoryOptions();
        $hasCategoryColumn = Book::hasCategoryColumn();
        $categoryCounts = collect();

        if ($hasCategoryColumn) {
            $categoryCounts = Book::selectRaw('category, COUNT(*) as total')
                ->whereNotNull('category')
                ->groupBy('category')
                ->pluck('total', 'category');
        }

        return view('admin.index', compact('equipment', 'categories', 'hasCategoryColumn', 'categoryCounts'));
    }

    public function create()
    {
        $categories = Book::categoryOptions();
        return view('admin.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'shelf' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:20',
            'isbn' => 'nullable|string|max:20|unique:books,isbn,NULL,id',
            'stock' => 'required|integer|min:1',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,jpe|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/books', 'public');
            $data['image'] = $imagePath;
        }

        Equipment::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Equipment $equipment)
    {
        $book = $equipment;
        return view('admin.show', compact('equipment', 'book'));
    }

    public function edit(Equipment $equipment)
    {
        $book = $equipment;
        $categories = Book::categoryOptions();
        return view('admin.edit', compact('equipment', 'book', 'categories'));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'shelf' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:20',
            'isbn' => 'nullable|string|max:20|unique:equipment,isbn,NULL,id,' . $equipment->id,
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,jpe|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $imagePath = $request->file('image')->store('images/books', 'public');
            $data['image'] = $imagePath;
        }

        $equipment->update($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Equipment $equipment)
    {
        // Delete image if exists
        if ($equipment->image && Storage::disk('public')->exists($equipment->image)) {
            Storage::disk('public')->delete($equipment->image);
        }

        $equipment->delete();

        return redirect()->route('equipment.index')->with('success', 'Peralatan berhasil dihapus.');
    }

    public function catalog(Request $request)
    {
        $query = Equipment::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul_buku', 'like', '%' . $search . '%')
                    ->orWhere('penulis', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        // Filter by availability
        if ($request->has('available') && $request->available == '1') {
            $query->where('stock', '>', 0);
        }

        $equipment = $query->paginate(12);

        return view('catalog', compact('equipment'));
    }

    public function borrow(Request $request, $equipmentId)
    {
        $user = auth()->user();
        $equipment = Equipment::findOrFail($equipmentId);

        // Check if equipment is available
        if ($equipment->stock <= 0) {
            return back()->with('error', 'Peralatan tidak tersedia untuk dipinjam.');
        }

        // Check if user already has this equipment borrowed
        $existingLoan = Loan::where('borrower_id', $user->id)
            ->where('status', 'active')
            ->whereHas('loanItems', function ($query) use ($equipmentId) {
                $query->where('equipment_id', $equipmentId);
            })
            ->first();

        if ($existingLoan) {
            return back()->with('error', 'Anda sudah meminjam peralatan ini.');
        }

        // Create loan
        $loan = Loan::create([
            'borrower_id' => $user->id,
            'loan_date' => now(),
            'due_date' => now()->addDays(7), // 7 days loan period
            'status' => 'active'
        ]);

        // Create loan item
        LoanItem::create([
            'loan_id' => $loan->id,
            'equipment_id' => $equipment->id,
            'quantity' => 1
        ]);

        // Decrease equipment stock
        $equipment->decrement('stock');

        return back()->with('success', 'Peralatan berhasil dipinjam! Silakan ambil di sekolah.');
    }
}
