<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Borrower;

class Loan extends Model
{
    protected $fillable = ['borrower_id', 'petugas_id', 'loan_date', 'due_date', 'return_date', 'status'];

    protected function casts(): array
    {
        return [
            'loan_date' => 'datetime',
            'due_date' => 'datetime',
            'return_date' => 'datetime',
        ];
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'loan_items', 'loan_id', 'book_id')->withPivot('quantity');
    }

    // ⬇ RELASI KE EXTENSIONS
    public function extensions()
    {
        return $this->hasMany(LoanExtension::class);
    }

    // ⬇ GET PENDING EXTENSION
    public function getPendingExtension()
    {
        return $this->extensions()->where('status', 'pending')->first();
    }
}
