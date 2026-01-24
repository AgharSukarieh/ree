<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EngineeringSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_id',
        'skill_name',
        'category_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'qr_id', 'qr_id');
    }

    public function category()
    {
        return $this->belongsTo(EngineeringSkillCategory::class, 'category_id');
    }
}
