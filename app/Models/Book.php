<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    protected static ?bool $hasCategoryColumnCache = null;

    public const CATEGORY_OPTIONS = [
        'Pelajaran',
        'Sejarah',
        'Sains',
        'Teknologi',
        'Matematika',
        'Bahasa',
        'Biografi',
        'Fiksi',
        'Non-Fiksi',
        'Referensi',
        'Pendidikan',
        'Lainnya',
    ];

    protected $fillable = [
        'title',
        'author',
        'category',
        'publisher',
        'year',
        'isbn',
        'stock',
        'description',
        'image',
    ];

    /**
     * Relasi ke LoanItem (many-to-many dengan Loan melalui loan_items)
     */
    public function loanItems()
    {
        return $this->hasMany(LoanItem::class, 'book_id');
    }

    /**
     * Relasi ke Loan (many-to-many)
     */
    public function loans()
    {
        return $this->belongsToMany(Loan::class, 'loan_items', 'book_id', 'loan_id')->withPivot('quantity');
    }

    /**
     * Relasi ke BookReview
     */
    public function reviews()
    {
        return $this->hasMany(BookReview::class, 'book_id');
    }

    /**
     * Relasi ke Wishlist
     */
    public function wishlistBy()
    {
        return $this->hasMany(Wishlist::class, 'book_id');
    }

    /**
     * Relasi ke BookReservation
     */
    public function reservations()
    {
        return $this->hasMany(BookReservation::class, 'book_id');
    }

    /**
     * Get average rating dari semua reviews
     */
    public function getAverageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews
     */
    public function getTotalReviews()
    {
        return $this->reviews()->count();
    }

    /**
     * Get total wishlist count
     */
    public function getWishlistCount()
    {
        return $this->wishlistBy()->count();
    }

    /**
     * Check if buku tersedia (stock > 0)
     */
    public function isAvailable()
    {
        return $this->stock > 0;
    }

    /**
     * Get status badge untuk stock
     */
    public function getStockBadge()
    {
        if ($this->stock <= 0) {
            return '<span class="badge bg-danger">Habis</span>';
        } elseif ($this->stock <= 3) {
            return '<span class="badge bg-warning">Terbatas (' . $this->stock . ')</span>';
        } else {
            return '<span class="badge bg-success">Tersedia (' . $this->stock . ')</span>';
        }
    }

    public static function categoryOptions(): array
    {
        return self::CATEGORY_OPTIONS;
    }

    public static function categoryBadgeStyle(?string $category): array
    {
        return match ($category) {
            'Pelajaran' => ['background' => '#e8f4ff', 'color' => '#1d4ed8'],
            'Sejarah' => ['background' => '#fff4e6', 'color' => '#c2410c'],
            'Sains' => ['background' => '#ecfdf3', 'color' => '#047857'],
            'Teknologi' => ['background' => '#eef2ff', 'color' => '#4338ca'],
            'Matematika' => ['background' => '#f5f3ff', 'color' => '#7c3aed'],
            'Bahasa' => ['background' => '#fff1f2', 'color' => '#be123c'],
            'Biografi' => ['background' => '#fefce8', 'color' => '#a16207'],
            'Fiksi' => ['background' => '#eff6ff', 'color' => '#2563eb'],
            'Non-Fiksi' => ['background' => '#f0fdf4', 'color' => '#15803d'],
            'Referensi' => ['background' => '#f8fafc', 'color' => '#475569'],
            'Pendidikan' => ['background' => '#ecfeff', 'color' => '#0f766e'],
            default => ['background' => '#f3f4f6', 'color' => '#4b5563'],
        };
    }

    public static function hasCategoryColumn(): bool
    {
        if (self::$hasCategoryColumnCache === null) {
            self::$hasCategoryColumnCache = Schema::hasColumn('books', 'category');
        }

        return self::$hasCategoryColumnCache;
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = ltrim($this->image, '/');

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }

        return asset($path);
    }
}
