<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanExtension extends Model
{
    protected $fillable = ['loan_id', 'old_due_date', 'new_due_date', 'status', 'reason', 'admin_notes', 'extension_days'];
    protected $dates = ['old_due_date', 'new_due_date'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
