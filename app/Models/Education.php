<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'degree_name',
        'field_of_study',
        'university_name',
        'start_year',
        'end_year',
    ];

    protected $casts = [
        'start_year' => 'date',
        'end_year' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }
}

