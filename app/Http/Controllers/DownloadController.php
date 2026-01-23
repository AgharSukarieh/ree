<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class DownloadController extends Controller
{
    /**
     * Generate and download PDF CV with 100% ATS-friendly structure
     */
    public function generatePdf($qr_id)
    {
        try {
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
                'softSkills'
            ])->where('status', 1)->find($qr_id);

            if (!$user) {
                abort(404, 'User not found');
            }
        } catch (\Exception $e) {
            abort(500, 'Error loading user data: ' . $e->getMessage());
        }

        // Fetch education data
        try {
            $education = DB::table('education')
                ->where('qr_id', $qr_id)
                ->get();
        } catch (\Exception $e) {
            $education = collect([]);
        }

        // ATS-Friendly CSS Structure
        $html = '<style>
            @page {
                margin: 1.5cm;
            }
            body { 
                font-family: "Arial", "Helvetica", sans-serif; 
                font-size: 10.5pt; 
                line-height: 1.4; 
                color: #000; 
                margin: 0; 
                padding: 0; 
                direction: rtl;
            }
            h1 { 
                font-size: 22pt; 
                font-weight: bold; 
                text-align: center; 
                margin: 0 0 5px 0; 
                text-transform: uppercase;
            }
            h2 { 
                font-size: 13pt; 
                font-weight: bold; 
                margin-top: 15px; 
                margin-bottom: 5px; 
                border-bottom: 1.5px solid #000; 
                padding-bottom: 2px; 
                text-transform: uppercase;
            }
            h3 { 
                font-size: 11pt; 
                font-weight: bold; 
                margin-top: 8px; 
                margin-bottom: 2px; 
            }
            .header-info { 
                text-align: center; 
                font-size: 9.5pt; 
                margin-bottom: 15px; 
            }
            .section { 
                margin-bottom: 12px; 
            }
            .item-header {
                display: block;
                margin-bottom: 2px;
            }
            .item-title {
                font-weight: bold;
                float: right;
            }
            .item-date {
                font-weight: bold;
                float: left;
                text-align: left;
            }
            .item-sub {
                clear: both;
                font-style: italic;
                margin-bottom: 4px;
            }
            ul { 
                padding-right: 20px; 
                margin: 4px 0; 
                list-style-type: disc;
            }
            li { 
                margin-bottom: 2px; 
            }
            .skills-container {
                margin-top: 5px;
            }
            .skill-group {
                margin-bottom: 4px;
            }
            .skill-label {
                font-weight: bold;
            }
            .clear {
                clear: both;
            }
        </style>';

        // 1. Header Section
        $html .= '<div class="header-info">';
        $html .= '<h1>' . htmlspecialchars($user->name) . '</h1>';
        
        $contact = [];
        if ($user->city) $contact[] = htmlspecialchars($user->city);
        if ($user->phone) $contact[] = htmlspecialchars($user->phone);
        if ($user->email) $contact[] = htmlspecialchars($user->email);
        if ($user->linkedin_profile) $contact[] = 'LinkedIn';
        
        $html .= '<div>' . implode(' | ', $contact) . '</div>';
        if ($user->job_title) {
            $html .= '<div style="font-weight: bold; margin-top: 5px;">' . htmlspecialchars($user->job_title) . '</div>';
        }
        $html .= '</div>';

        // 2. Professional Summary
        if ($user->profile_summary) {
            $html .= '<div class="section"><h2>الملخص المهني</h2>';
            $html .= '<p>' . nl2br(htmlspecialchars($user->profile_summary)) . '</p></div>';
        }

        // 3. Work Experience
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section"><h2>الخبرة العملية</h2>';
            foreach ($user->experiences as $exp) {
                $html .= '<div class="item-header">';
                $html .= '<span class="item-title">' . htmlspecialchars($exp->title) . '</span>';
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'الحالي';
                $html .= '<span class="item-date">' . $startDate . ' – ' . $endDate . '</span>';
                $html .= '</div><div class="clear"></div>';
                
                $html .= '<div class="item-sub">' . htmlspecialchars($exp->company) . ($exp->location ? ' | ' . htmlspecialchars($exp->location) : '') . '</div>';
                
                if ($exp->description) {
                    $html .= '<ul>';
                    foreach (explode("\n", $exp->description) as $line) {
                        $line = trim(preg_replace('/^[-•*]\s*/', '', $line));
                        if ($line) $html .= '<li>' . htmlspecialchars($line) . '</li>';
                    }
                    $html .= '</ul>';
                }
            }
            $html .= '</div>';
        }

        // 4. Education
        if ($education->count() > 0) {
            $html .= '<div class="section"><h2>التعليم</h2>';
            foreach ($education as $edu) {
                $html .= '<div class="item-header">';
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $html .= '<span class="item-title">' . htmlspecialchars($degree) . '</span>';
                $startYear = $edu->start_year ?? '';
                $endYear = ($edu->end_year ?? 0) == 0 ? 'الحالي' : ($edu->end_year ?? '');
                $html .= '<span class="item-date">' . $startYear . ' – ' . $endYear . '</span>';
                $html .= '</div><div class="clear"></div>';
                $html .= '<div class="item-sub">' . htmlspecialchars($edu->university_name ?? $edu->university ?? '') . '</div>';
            }
            $html .= '</div>';
        }

        // 5. Skills (Technical & Soft)
        $html .= '<div class="section"><h2>المهارات</h2><div class="skills-container">';
        
        // Technical Skills by Category
        if ($user->skills->count() > 0) {
            foreach ($user->skills->groupBy('category.category_name') as $cat => $skills) {
                $html .= '<div class="skill-group"><span class="skill-label">' . htmlspecialchars($cat ?: 'مهارات تقنية') . ':</span> ';
                $html .= htmlspecialchars($skills->pluck('skill_name')->implode('، ')) . '</div>';
            }
        }

        // Soft Skills
        if ($user->softSkills->count() > 0) {
            $html .= '<div class="skill-group"><span class="skill-label">المهارات الشخصية:</span> ';
            $html .= htmlspecialchars($user->softSkills->pluck('soft_name')->implode('، ')) . '</div>';
        }

        // Languages
        if ($user->languages->count() > 0) {
            $html .= '<div class="skill-group"><span class="skill-label">اللغات:</span> ';
            $langs = $user->languages->map(function($l) { return $l->language_name . ' (' . $l->proficiency_level . ')'; });
            $html .= htmlspecialchars($langs->implode('، ')) . '</div>';
        }
        $html .= '</div></div>';

        // 6. Projects
        if ($user->projects->count() > 0) {
            $html .= '<div class="section"><h2>المشاريع</h2>';
            foreach ($user->projects as $proj) {
                $html .= '<div class="item-header"><span class="item-title">' . htmlspecialchars($proj->project_title) . '</span></div><div class="clear"></div>';
                if ($proj->description) {
                    $html .= '<ul>';
                    foreach (explode("\n", $proj->description) as $line) {
                        $line = trim(preg_replace('/^[-•*]\s*/', '', $line));
                        if ($line) $html .= '<li>' . htmlspecialchars($line) . '</li>';
                    }
                    $html .= '</ul>';
                }
            }
            $html .= '</div>';
        }

        // 7. Certifications
        if ($user->certifications->count() > 0) {
            $html .= '<div class="section"><h2>الشهادات</h2><ul>';
            foreach ($user->certifications as $c) {
                $date = $c->issue_date ? ' (' . date('M Y', strtotime($c->issue_date)) . ')' : '';
                $html .= '<li>' . htmlspecialchars($c->certifications_name . ' – ' . $c->issuing_org) . $date . '</li>';
            }
            $html .= '</ul></div>';
        }

        // PDF Generation
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'default_font' => 'Arial'
            ]);
            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($html);
            return $mpdf->Output('CV_' . time() . '.pdf', 'D');
        } catch (\Exception $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }
}
