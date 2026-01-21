<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalSkillCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
    ];

    public function medicalSkills()
    {
        return $this->hasMany(MedicalSkill::class, 'category_id');
    }
}