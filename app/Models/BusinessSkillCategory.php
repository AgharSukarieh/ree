<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSkillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
    ];

    public function businessSkills()
    {
        return $this->hasMany(BusinessSkill::class, 'category_id');
    }
}
