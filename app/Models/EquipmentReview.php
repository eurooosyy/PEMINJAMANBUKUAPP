<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentReview extends Model
{
    protected $table = 'equipment_reviews';
    protected $fillable = ['equipment_id', 'user_id', 'rating', 'review', 'helpful_count'];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAverageRating()
    {
        return self::where('equipment_id', $this->equipment_id)->avg('rating');
    }
}
