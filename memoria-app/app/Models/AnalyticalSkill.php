<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticalSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'skill_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }
}