<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appartements extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'price',
        'location',
        'latitude',
        'longitude',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function photos()
    {
        return $this->hasMany(photos::class, 'appartement_id');
    }
    public function categories()
    {
        return $this->belongsToMany(categories::class, 'appartement_category', 'appartement_id', 'category_id');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'appartement_id');
    }
}
