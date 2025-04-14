<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appartements extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
        public function photos()
    {
        return $this->hasMany(photos::class);
    }
    public function categories()
    {
        return $this->belongsToMany(categories::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
