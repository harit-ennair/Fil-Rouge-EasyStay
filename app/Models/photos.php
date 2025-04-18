<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class photos extends Model
{
    use HasFactory;
    protected $fillable = [
        'photo_path',
        'appartement_id',
    ];

    public function appartement()
    {
        return $this->belongsTo(appartements::class, 'appartement_id');
    }
}
