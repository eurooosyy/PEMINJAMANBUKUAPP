<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role; // ⬅ TAMBAHKAN

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // ⬅ TAMBAHKAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ⬇ RELASI KE ROLE
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // ⬇ RELASI LOANS SEBAGAI PEMINJAM
    public function loansAsBorrower()
    {
        return $this->hasMany(Loan::class, 'borrower_id');
    }

    // ⬇ RELASI LOANS SEBAGAI PETUGAS
    public function loansAsStaff()
    {
        return $this->hasMany(Loan::class, 'petugas_id');
    }

    // ⬇ RELASI KE BOOK REVIEWS
    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }

    // ⬇ RELASI KE WISHLIST
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    // ⬇ RELASI KE RESERVATIONS
    public function reservations()
    {
        return $this->hasMany(BookReservation::class);
    }

    // ⬇ RELASI KE NOTIFICATIONS
    public function notifications()
    {
        return $this->hasMany(StudentNotification::class);
    }
}
