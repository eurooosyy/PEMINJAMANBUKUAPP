<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;
use App\Models\Equipment;

class LoanItem extends Model
{
    protected $fillable = ['loan_id', 'book_id', 'quantity'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class, 'book_id');
    }

    // equipment relation added for compatibility
}
