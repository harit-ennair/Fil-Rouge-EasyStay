<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function appartements()
    {
        return $this->belongsToMany(appartements::class, 'appartement_category', 'category_id', 'appartement_id');
    }
}
