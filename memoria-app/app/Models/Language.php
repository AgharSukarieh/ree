<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'language_name',
        'proficiency_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }
}