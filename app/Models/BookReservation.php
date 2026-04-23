<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReservation extends Model // DELETED - Use EquipmentReservation instead
{
    protected $fillable = ['book_id', 'user_id', 'status', 'reserved_until'];
    protected $dates = ['reserved_until'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPendingReservationCount()
    {
        return self::where('user_id', $this->user_id)
            ->where('status', 'pending')
            ->count();
    }
}
