<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    protected $table = 'book_reviews';
    protected $fillable = ['book_id', 'user_id', 'rating', 'review', 'helpful_count'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAverageRating()
    {
        return self::where('book_id', $this->book_id)->avg('rating');
    }
}
