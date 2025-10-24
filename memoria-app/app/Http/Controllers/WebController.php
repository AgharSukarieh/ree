<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function profile($qr_id)
    {
        $user = User::with([
            'activities',
            'analyticalSkills',
            'certifications',
            'coreCompetencies',
            'experiences',
            'interests',
            'languages',
            'medicalSkills.category',
            'memberships',
            'projects',
            'research',
            'skills.category',
            'softSkills',
            'wishes'
        ])->where('status', 1)->find($qr_id);

        if (!$user) {
            abort(404, 'Profile not found or inactive');
        }

        return view('profile', compact('user'));
    }

    public function dashboard()
    {
        $users = User::where('status', 1)->get();
        return view('dashboard', compact('users'));
    }
}
