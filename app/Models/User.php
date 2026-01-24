<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'qr_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'qr_id',
        'name',
        'phone',
        'city',
        'job_title',
        'profile_summary',
        'email',
        'linkedin_profile',
        'github_profile',
        'profile_website',
        'profile_image',
        'major',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    // Relationships
    public function activities()
    {
        return $this->hasMany(Activity::class, 'qr_id', 'qr_id');
    }

    public function analyticalSkills()
    {
        return $this->hasMany(AnalyticalSkill::class, 'qr_id', 'qr_id');
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'qr_id', 'qr_id');
    }

    public function coreCompetencies()
    {
        return $this->hasMany(CoreCompetency::class, 'qr_id', 'qr_id');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'qr_id', 'qr_id');
    }

    public function interests()
    {
        return $this->hasMany(Interest::class, 'qr_id', 'qr_id');
    }

    public function languages()
    {
        return $this->hasMany(Language::class, 'qr_id', 'qr_id');
    }

    public function medicalSkills()
    {
        return $this->hasMany(MedicalSkill::class, 'qr_id', 'qr_id');
    }

    public function businessSkills()
    {
        return $this->hasMany(BusinessSkill::class, 'qr_id', 'qr_id');
    }

    public function memberships()
    {
        return $this->hasMany(Membership::class, 'qr_id', 'qr_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'qr_id', 'qr_id');
    }

    public function research()
    {
        return $this->hasMany(Research::class, 'qr_id', 'qr_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'qr_id', 'qr_id');
    }

    public function softSkills()
    {
        return $this->hasMany(SoftSkill::class, 'qr_id', 'qr_id');
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class, 'qr_id', 'qr_id');
    }
}