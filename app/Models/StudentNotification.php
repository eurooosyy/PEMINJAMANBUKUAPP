<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    protected $table = 'student_notifications';
    protected $fillable = ['user_id', 'type', 'title', 'message', 'related_loan_id', 'related_book_id', 'is_read', 'read_at'];
    protected $dates = ['read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'related_loan_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'related_book_id');
    }

    public function markAsRead()
    {
        $this->is_read = true;
        $this->read_at = now();
        $this->save();
    }

    public function getUnreadCount($userId)
    {
        return self::where('user_id', $userId)->where('is_read', false)->count();
    }
}