<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $fillable = ['user_id', 'nis', 'class', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getNameAttribute()
    {
        return $this->user?->name;
    }

    public function getEmailAttribute()
    {
        return $this->user?->email;
    }
}
