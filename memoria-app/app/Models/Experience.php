<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'title',
        'company',
        'location',
        'start_date',
        'end_date',
        'description',
        'is_internship',
    ];

    protected $casts = [
        'end_date' => 'date',
        'is_internship' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }
}