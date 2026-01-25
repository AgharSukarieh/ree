<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class DownloadControllerStayleTow extends \App\Http\Controllers\Controller
{
    /**
     * Generate and download PDF CV (100% ATS-Optimized)
     * Single-column layout optimized for Applicant Tracking Systems
     */
    public function generatePdf($qr_id)
    {
        try {
            $user = User::with([
                'activities',
                'analyticalSkills',
                'businessSkills.category',
                'certifications',
                'coreCompetencies',
                'education',
                'engineeringSkills.category',
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
                ->orderBy('end_year', 'desc')
                ->orderBy('start_year', 'desc')
                ->get();
        } catch (\Exception $e) {
            $education = collect([]);
        }

        // Generate optimized professional summary for ATS
        $summary = $this->generateProfessionalSummary($user, $education);

        // Build ATS-optimized HTML (single-column layout)
        $html = $this->buildATSOptimizedHtml($user, $education, $summary);

        // Generate PDF using MPDF with ATS-optimized settings
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 15,
                'margin_bottom' => 15,
                'tempDir' => sys_get_temp_dir(),
                'default_font' => 'arial',
                'allow_charset_conversion' => true
            ]);
            
            // ATS-friendly settings
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->shrink_tables_to_fit = 1;
            $mpdf->SetHTMLFooter('');
            $mpdf->SetAutoPageBreak(true, 15);
            
            $mpdf->WriteHTML($html);
            
            $fileName = 'CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.pdf';
            return $mpdf->Output($fileName, 'D');
            
        } catch (\Exception $e) {
            abort(500, 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate optimized professional summary for ATS
     */
    private function generateProfessionalSummary($user, $education)
    {
        $summary = $user->profile_summary ?? '';
        
        // If summary exists and is substantial, use it
        if (!empty($summary) && strlen(trim($summary)) > 50) {
            return $this->enhanceSummary($summary, $user);
        }

        // Generate summary from available data
        $parts = [];
        
        if ($user->job_title) {
            $parts[] = $user->job_title;
        }
        
        if ($user->major) {
            $majorText = $this->formatMajor($user->major);
            $parts[] = "specialized in {$majorText}";
        }
        
        if ($user->skills->count() > 0) {
            $topSkills = $user->skills->take(3)->pluck('skill_name')->toArray();
            if (!empty($topSkills)) {
                $parts[] = "proficient in " . implode(', ', $topSkills);
            }
        }
        
        if ($user->experiences->count() > 0) {
            $parts[] = "with proven experience in";
            $expTypes = $user->experiences->pluck('title')->unique()->take(2)->toArray();
            if (!empty($expTypes)) {
                $parts[] = strtolower(implode(' and ', $expTypes));
            }
        }
        
        $parts[] = "focused on delivering high-quality solutions and driving results";
        
        $generated = ucfirst(implode(' ', $parts)) . '.';
        
        return $this->enhanceSummary($generated, $user);
    }

    /**
     * Enhance summary with proper formatting for ATS
     */
    private function enhanceSummary($summary, $user)
    {
        $summary = trim($summary);
        $summary = preg_replace('/\s+/', ' ', $summary);
        $summary = ucfirst($summary);
        
        if (!preg_match('/[.!?]$/', $summary)) {
            $summary .= '.';
        }
        
        // Limit length for ATS (max 150 words)
        $sentences = preg_split('/(?<=[.!?])\s+/', $summary);
        $final = [];
        $wordCount = 0;
        
        foreach ($sentences as $sentence) {
            $words = str_word_count($sentence);
            if ($wordCount + $words <= 150) {
                $final[] = trim($sentence);
                $wordCount += $words;
            } else {
                break;
            }
        }
        
        return implode(' ', $final);
    }

    /**
     * Format major for display
     */
    private function formatMajor($major)
    {
        $majors = [
            'IT' => 'Information Technology',
            'Medicine' => 'Medicine',
            'Business' => 'Business Administration',
            'Engineering' => 'Engineering'
        ];
        
        return $majors[$major] ?? $major;
    }

    /**
     * Collect all skills into a single unified list
     */
    private function collectAllSkills($user)
    {
        $allSkills = [];
        
        // Technical Skills
        foreach ($user->skills as $skill) {
            if ($skill->skill_name && trim($skill->skill_name)) {
                $allSkills[] = trim($skill->skill_name);
            }
        }
        
        // Soft Skills
        foreach ($user->softSkills as $skill) {
            if ($skill->soft_name && trim($skill->soft_name)) {
                $allSkills[] = trim($skill->soft_name);
            }
        }
        
        // Business Skills
        foreach ($user->businessSkills as $skill) {
            if ($skill->skill_name && trim($skill->skill_name)) {
                $allSkills[] = trim($skill->skill_name);
            }
        }
        
        // Engineering Skills
        foreach ($user->engineeringSkills as $skill) {
            if ($skill->skill_name && trim($skill->skill_name)) {
                $allSkills[] = trim($skill->skill_name);
            }
        }
        
        // Medical Skills
        foreach ($user->medicalSkills as $skill) {
            if ($skill->skill_name && trim($skill->skill_name)) {
                $allSkills[] = trim($skill->skill_name);
            }
        }
        
        // Analytical Skills
        if ($user->major === 'IT') {
            foreach ($user->analyticalSkills as $skill) {
                if ($skill->skill_name && trim($skill->skill_name)) {
                    $allSkills[] = trim($skill->skill_name);
                }
            }
        }
        
        // Remove duplicates and return
        return array_unique($allSkills);
    }

    /**
     * Build 100% ATS-Optimized HTML
     * Single-column layout with standard section titles
     */
    private function buildATSOptimizedHtml($user, $education, $summary)
    {
        $primaryColor = '#3E5C9A';
        $textColor = '#333333';
        $lightTextColor = '#666666';

        $html = '
        <style>
            @page { margin: 30pt; }
            body { 
                font-family: Arial, Helvetica, sans-serif; 
                color: ' . $textColor . '; 
                line-height: 1.6; 
                font-size: 10pt;
                margin: 0;
                padding: 0;
            }
            
            /* Header */
            .header { 
                margin-bottom: 20pt; 
                padding-bottom: 12pt;
                border-bottom: 1pt solid #dddddd;
            }
            .name { 
                font-size: 24pt; 
                font-weight: bold; 
                color: ' . $primaryColor . '; 
                margin-bottom: 4pt;
            }
            .job-title {
                font-size: 12pt;
                font-weight: bold;
                color: #444444;
                margin-bottom: 8pt;
            }
            .contact-header {
                font-size: 9pt;
                color: ' . $textColor . ';
                margin-bottom: 8pt;
                line-height: 1.5;
            }
            .summary {
                font-size: 10pt;
                color: ' . $textColor . ';
                margin-bottom: 20pt;
                text-align: justify;
                line-height: 1.7;
            }
            
            /* Section Titles - Standard ATS format */
            .section-title {
                font-size: 12pt;
                font-weight: bold;
                color: ' . $primaryColor . ';
                margin-bottom: 10pt;
                margin-top: 18pt;
                text-transform: uppercase;
                border-bottom: 1pt solid #dddddd;
                padding-bottom: 3pt;
                page-break-after: avoid;
            }
            
            /* Experience & Education Items */
            .item { 
                margin-bottom: 14pt; 
                padding-bottom: 8pt;
                page-break-inside: avoid;
            }
            .item-header { 
                font-weight: bold; 
                font-size: 10.5pt; 
                color: #000000; 
                margin-bottom: 3pt;
            }
            .item-sub { 
                font-weight: bold; 
                color: #555555; 
                font-size: 10pt; 
                margin-bottom: 2pt;
            }
            .item-date { 
                color: ' . $lightTextColor . '; 
                font-size: 9pt; 
                margin-bottom: 4pt; 
                font-style: italic; 
            }
            
            .bullet-list { 
                margin-top: 4pt; 
                margin-bottom: 6pt; 
                padding-left: 16pt; 
                page-break-inside: avoid;
            }
            .bullet-item { 
                margin-bottom: 2pt; 
                list-style-type: disc; 
            }
            
            .skill-list {
                margin: 0;
                padding-left: 0;
                list-style: none;
            }
            .skill-item {
                display: inline;
                margin-right: 8pt;
                font-size: 9.5pt;
            }
            .skill-item:after {
                content: ", ";
            }
            .skill-item:last-child:after {
                content: "";
            }
        </style>

        <div>
            <!-- Header -->
            <div class="header">
                <div class="name">' . htmlspecialchars(strtoupper($user->name)) . '</div>';
        
        if ($user->job_title) {
            $html .= '<div class="job-title">' . htmlspecialchars($user->job_title) . '</div>';
        }
        
        // Contact information in header
        $contactInfo = [];
        if ($user->city && trim($user->city)) {
            $contactInfo[] = htmlspecialchars($user->city);
        }
        if ($user->phone && trim($user->phone)) {
            $contactInfo[] = htmlspecialchars($user->phone);
        }
        if ($user->email && trim($user->email)) {
            $contactInfo[] = htmlspecialchars($user->email);
        }
        
        // Profile URL
        $profileUrl = route('profile', $user->qr_id);
        $contactInfo[] = 'Profile: ' . htmlspecialchars($profileUrl);
        
        if ($user->linkedin_profile && trim($user->linkedin_profile)) {
            $link = $user->linkedin_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $contactInfo[] = 'LinkedIn: ' . htmlspecialchars($link);
        }
        if ($user->github_profile && trim($user->github_profile)) {
            $link = $user->github_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $contactInfo[] = 'GitHub: ' . htmlspecialchars($link);
        }
        if ($user->profile_website && trim($user->profile_website)) {
            $link = $user->profile_website;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $contactInfo[] = 'Portfolio: ' . htmlspecialchars($link);
        }
        
        if (!empty($contactInfo)) {
            $html .= '<div class="contact-header">' . implode(' | ', $contactInfo) . '</div>';
        }
        
        // Summary Section (ATS standard: "Summary")
        if ($summary && trim($summary)) {
            $html .= '<div class="summary">
                    <div class="section-title">Summary</div>
                    <div>' . nl2br(htmlspecialchars($summary)) . '</div>
                </div>';
        } else if ($user->profile_summary && trim($user->profile_summary)) {
            $html .= '<div class="summary">
                    <div class="section-title">Summary</div>
                    <div>' . nl2br(htmlspecialchars($user->profile_summary)) . '</div>
                </div>';
        }
        
        $html .= '</div>';

        // Skills Section - Consolidated (ATS standard: "Skills")
        $allSkills = $this->collectAllSkills($user);
        if (!empty($allSkills)) {
            $html .= '<div class="section-title">Skills</div>';
            $html .= '<ul class="skill-list">';
            foreach ($allSkills as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill) . '</li>';
            }
            $html .= '</ul>';
        }

        // Work Experience Section (ATS standard: "Work Experience")
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section-title">Work Experience</div>';

            $experiences = $user->experiences->sortByDesc(function($exp) {
                return $exp->start_date ? strtotime($exp->start_date) : 0;
            });

            foreach ($experiences as $exp) {
                $html .= '<div class="item">
                        <div class="item-header">' . htmlspecialchars($exp->title) . '</div>';
                
                $companyLine = [];
                if ($exp->company && trim($exp->company)) {
                    $companyLine[] = htmlspecialchars($exp->company);
                }
                if ($exp->location && trim($exp->location)) {
                    $companyLine[] = htmlspecialchars($exp->location);
                }
                
                if (!empty($companyLine)) {
                    $html .= '<div class="item-sub">' . implode(' - ', $companyLine) . '</div>';
                }
                
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $dateText = $startDate . ' - ' . $endDate;
                if ($exp->is_internship) {
                    $dateText .= ' (Internship)';
                }
                $html .= '<div class="item-date">' . $dateText . '</div>';
                
                $description = $exp->description ?? $exp->responsibilities ?? '';
                if ($description && trim($description)) {
                    $responsibilities = explode("\n", $description);
                    $validItems = [];
                    foreach ($responsibilities as $res) {
                        $res = trim($res);
                        if (!empty($res)) {
                            $res = preg_replace('/^[-•*]\s*/', '', $res);
                            $res = trim($res);
                            if (!empty($res)) {
                                $validItems[] = $res;
                            }
                        }
                    }
                    
                    if (!empty($validItems)) {
                        $html .= '<ul class="bullet-list">';
                        foreach ($validItems as $item) {
                            $html .= '<li class="bullet-item">' . htmlspecialchars($item) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                
                $html .= '</div>';
            }
        }

        // Education Section (ATS standard: "Education")
        if ($education->count() > 0) {
            $html .= '<div class="section-title">Education</div>';

            foreach ($education as $edu) {
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $university = $edu->university_name ?? $edu->institution ?? '';
                $field = $edu->field_of_study ?? '';
                
                if ($degree || $university) {
                    $html .= '<div class="item">
                            <div class="item-header">';
                    
                    if ($degree) {
                        $degreeText = htmlspecialchars($degree);
                        if ($field) {
                            $degreeText .= ' in ' . htmlspecialchars($field);
                        }
                        $html .= $degreeText;
                    } else if ($field) {
                        $html .= htmlspecialchars($field);
                    }
                    
                    $html .= '</div>';
                    
                    if ($university) {
                        $html .= '<div class="item-sub">' . htmlspecialchars($university) . '</div>';
                    }
                    
                    $startYear = $edu->start_year ? date('Y', strtotime($edu->start_year)) : '';
                    $endYear = ($edu->end_year && $edu->end_year != '0000-00-00') ? date('Y', strtotime($edu->end_year)) : 'Present';
                    if ($startYear || $endYear) {
                        $html .= '<div class="item-date">' . $startYear . ' - ' . $endYear . '</div>';
                    }
                    
                    $html .= '</div>';
                }
            }
        }

        // Projects Section
        if ($user->projects->count() > 0) {
            $html .= '<div class="section-title">Projects</div>';
            
            foreach ($user->projects as $proj) {
                $html .= '<div class="item">
                        <div class="item-header">' . htmlspecialchars($proj->project_title) . '</div>';
                
                if ($proj->description && trim($proj->description)) {
                    $description = trim($proj->description);
                    $lines = explode("\n", $description);
                    $validLines = [];
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            $line = preg_replace('/^[-•*]\s*/', '', $line);
                            $line = trim($line);
                            if (!empty($line)) {
                                $validLines[] = $line;
                            }
                        }
                    }
                    
                    if (!empty($validLines)) {
                        $html .= '<ul class="bullet-list">';
                        foreach ($validLines as $line) {
                            $html .= '<li class="bullet-item">' . htmlspecialchars($line) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                
                if ($proj->technologies_used && trim($proj->technologies_used)) {
                    $html .= '<div style="font-size: 9pt; color: ' . $lightTextColor . '; margin-top: 3pt;"><strong>Technologies:</strong> ' . htmlspecialchars($proj->technologies_used) . '</div>';
                }
                
                if ($proj->link && trim($proj->link)) {
                    $link = $proj->link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;">Link: <a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">' . htmlspecialchars($link) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }

        // Certifications Section
        $hasCertifications = false;
        foreach ($user->certifications as $cert) {
            $certName = $cert->certifications_name ?? $cert->certificate_name ?? '';
            if ($certName && trim($certName)) {
                $hasCertifications = true;
                break;
            }
        }
        
        if ($hasCertifications) {
            $html .= '<div class="section-title">Certifications</div>';
            foreach ($user->certifications as $cert) {
                $certName = $cert->certifications_name ?? $cert->certificate_name ?? '';
                if ($certName && trim($certName)) {
                    $html .= '<div class="item">
                            <div class="item-header">' . htmlspecialchars($certName) . '</div>';
                    
                    if ($cert->issuing_org && trim($cert->issuing_org)) {
                        $html .= '<div class="item-sub">' . htmlspecialchars($cert->issuing_org) . '</div>';
                    }
                    
                    if ($cert->issue_date) {
                        $issueDate = date('M Y', strtotime($cert->issue_date));
                        $certText = $issueDate;
                        if ($cert->expiration_date && $cert->expiration_date != '0000-00-00') {
                            $expDate = date('M Y', strtotime($cert->expiration_date));
                            $certText .= ' - ' . $expDate;
                        }
                        $html .= '<div class="item-date">' . $certText . '</div>';
                    }
                    
                    if ($cert->link_driver && trim($cert->link_driver)) {
                        $link = $cert->link_driver;
                        if (!preg_match('/^https?:\/\//', $link)) {
                            $link = 'https://' . $link;
                        }
                        $html .= '<div style="font-size: 9pt; margin-top: 3pt;">Link: <a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">' . htmlspecialchars($link) . '</a></div>';
                    }
                    
                    $html .= '</div>';
                }
            }
        }

        // Languages Section
        if ($user->languages->count() > 0) {
            $html .= '<div class="section-title">Languages</div>';
            $html .= '<ul class="skill-list">';
            foreach ($user->languages as $lang) {
                $proficiency = $lang->proficiency_level ?? $lang->proficiency ?? '';
                if ($proficiency) {
                    $html .= '<li class="skill-item">' . htmlspecialchars($lang->language_name) . ' (' . htmlspecialchars($proficiency) . ')</li>';
                } else {
                    $html .= '<li class="skill-item">' . htmlspecialchars($lang->language_name) . '</li>';
                }
            }
            $html .= '</ul>';
        }

        // Professional Memberships
        if ($user->memberships->count() > 0) {
            $html .= '<div class="section-title">Professional Memberships</div>';
            foreach ($user->memberships as $m) {
                if ($m->organization_name && trim($m->organization_name)) {
                    $html .= '<div class="item">
                            <div class="item-header">' . htmlspecialchars($m->organization_name) . '</div>';
                    
                    if ($m->membership_type && trim($m->membership_type)) {
                        $html .= '<div class="item-sub">' . htmlspecialchars($m->membership_type) . '</div>';
                    }
                    
                    if ($m->start_date_membership || $m->end_date_membership) {
                        $startDate = $m->start_date_membership ? date('M Y', strtotime($m->start_date_membership)) : '';
                        $endDate = $m->end_date_membership ? date('M Y', strtotime($m->end_date_membership)) : 'Present';
                        if ($startDate || $endDate) {
                            $html .= '<div class="item-date">' . $startDate . ' - ' . $endDate . '</div>';
                        }
                    }
                    
                    if ($m->membership_status && trim($m->membership_status)) {
                        $html .= '<div style="font-size: 9pt; color: ' . $lightTextColor . ';">Status: ' . htmlspecialchars($m->membership_status) . '</div>';
                    }
                    
                    $html .= '</div>';
                }
            }
        }

        // Research Section (for Medical majors)
        if ($user->research->count() > 0 && $user->major === 'Medicine') {
            $html .= '<div class="section-title">Research</div>';
            
            foreach ($user->research as $res) {
                $html .= '<div class="item">
                        <div class="item-header">' . htmlspecialchars($res->title) . '</div>';
                
                if ($res->publication_year) {
                    $html .= '<div class="item-date">' . htmlspecialchars($res->publication_year) . '</div>';
                }
                
                if ($res->description && trim($res->description)) {
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;">' . nl2br(htmlspecialchars($res->description)) . '</div>';
                }
                
                if ($res->link && trim($res->link)) {
                    $link = $res->link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;">Link: <a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">' . htmlspecialchars($link) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }

        // Activities Section
        if ($user->activities->count() > 0) {
            $html .= '<div class="section-title">Activities</div>';
            
            foreach ($user->activities as $act) {
                $html .= '<div class="item">
                        <div class="item-header">' . htmlspecialchars($act->activity_title) . '</div>';
                
                if ($act->organization) {
                    $html .= '<div class="item-sub">' . htmlspecialchars($act->organization) . '</div>';
                }
                
                if ($act->activity_date) {
                    $html .= '<div class="item-date">' . date('M Y', strtotime($act->activity_date)) . '</div>';
                }
                
                if ($act->description_activity && trim($act->description_activity)) {
                    $description = trim($act->description_activity);
                    $lines = explode("\n", $description);
                    $validLines = [];
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (!empty($line)) {
                            $line = preg_replace('/^[-•*]\s*/', '', $line);
                            $line = trim($line);
                            if (!empty($line)) {
                                $validLines[] = $line;
                            }
                        }
                    }
                    
                    if (!empty($validLines)) {
                        $html .= '<ul class="bullet-list">';
                        foreach ($validLines as $line) {
                            $html .= '<li class="bullet-item">' . htmlspecialchars($line) . '</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                
                if ($act->activity_link && trim($act->activity_link)) {
                    $link = $act->activity_link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;">Link: <a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">' . htmlspecialchars($link) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }
        
        $html .= '</div>';

        return $html;
    }
}
