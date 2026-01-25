<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class DownloadControllerStayleTow extends \App\Http\Controllers\Controller
{
    /**
     * Generate and download PDF CV (High-End Design & ATS-Optimized)
     * This version is designed to match the two-column professional layout.
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

        // Prepare HTML with the high-end modern design (ATS-optimized)
        $html = $this->buildModernATSHtml($user, $education, $summary);

        // Generate PDF using MPDF (ATS-optimized settings)
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
            
            // Enable text selection (important for ATS parsing)
            $mpdf->shrink_tables_to_fit = 1;
            
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
        
        // Job title and years of experience
        if ($user->job_title) {
            $parts[] = $user->job_title;
        }
        
        // Major/field
        if ($user->major) {
            $majorText = $this->formatMajor($user->major);
            $parts[] = "specialized in {$majorText}";
        }
        
        // Key skills mention
        if ($user->skills->count() > 0) {
            $topSkills = $user->skills->take(3)->pluck('skill_name')->toArray();
            if (!empty($topSkills)) {
                $parts[] = "proficient in " . implode(', ', $topSkills);
            }
        }
        
        // Experience level
        if ($user->experiences->count() > 0) {
            $parts[] = "with proven experience in";
            $expTypes = $user->experiences->pluck('title')->unique()->take(2)->toArray();
            if (!empty($expTypes)) {
                $parts[] = strtolower(implode(' and ', $expTypes));
            }
        }
        
        // Value proposition
        $parts[] = "focused on delivering high-quality solutions and driving results";
        
        $generated = ucfirst(implode(' ', $parts)) . '.';
        
        return $this->enhanceSummary($generated, $user);
    }

    /**
     * Enhance summary with action verbs and keywords
     */
    private function enhanceSummary($summary, $user)
    {
        // Clean and normalize
        $summary = trim($summary);
        $summary = preg_replace('/\s+/', ' ', $summary);
        
        // Ensure it starts with capital and ends with period
        $summary = ucfirst($summary);
        if (!preg_match('/[.!?]$/', $summary)) {
            $summary .= '.';
        }
        
        // Limit length for ATS (2-3 sentences, max 150 words)
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
     * Build Modern, High-End, ATS-Optimized HTML
     * Layout: Two-column structure with professional blue accents.
     */
    private function buildModernATSHtml($user, $education, $summary)
    {
        $primaryColor = '#3E5C9A'; // Professional Blue
        $textColor = '#333333';
        $lightTextColor = '#666666';

        $html = '
        <style>
            @page { margin: 30pt; }
            body { 
                font-family: "Arial", "Helvetica", sans-serif; 
                color: ' . $textColor . '; 
                line-height: 1.5; 
                font-size: 10pt;
                margin: 0;
                padding: 0;
            }
            .container { width: 100%; }
            
            /* Header Section */
            .header { margin-bottom: 20pt; }
            .name { 
                font-size: 26pt; 
                font-weight: bold; 
                color: ' . $primaryColor . '; 
                margin-bottom: 2pt;
                text-transform: capitalize;
            }
            .job-title {
                font-size: 13pt;
                font-weight: bold;
                color: #444444;
                margin-bottom: 10pt;
            }
            .summary {
                font-size: 10pt;
                color: ' . $textColor . ';
                margin-bottom: 20pt;
                text-align: justify;
                width: 100%;
            }
            
            /* Layout Columns */
            .main-content { width: 68%; float: left; }
            .sidebar { width: 28%; float: right; }
            
            /* Section Styling */
            .section-title {
                font-size: 12pt;
                font-weight: bold;
                color: ' . $primaryColor . ';
                margin-bottom: 8pt;
                margin-top: 15pt;
                text-transform: uppercase;
                border-bottom: 0.5pt solid #dddddd;
                padding-bottom: 2pt;
            }
            
            /* Experience & Education Items */
            .item { margin-bottom: 12pt; }
            .item-header { font-weight: bold; font-size: 10.5pt; color: #000000; }
            .item-sub { font-weight: bold; color: #555555; font-size: 10pt; }
            .item-date { color: ' . $lightTextColor . '; font-size: 9pt; margin-bottom: 3pt; font-style: italic; }
            
            .bullet-list { margin-top: 4pt; margin-bottom: 8pt; padding-left: 12pt; }
            .bullet-item { margin-bottom: 2pt; list-style-type: disc; }
            
            /* Sidebar Styling */
            .sidebar-section { margin-bottom: 18pt; }
            .sidebar-title {
                font-size: 11pt;
                font-weight: bold;
                color: ' . $primaryColor . ';
                margin-bottom: 6pt;
                border-bottom: 0.5pt solid #dddddd;
                padding-bottom: 2pt;
            }
            .contact-info { font-size: 9pt; margin-bottom: 4pt; color: ' . $textColor . '; }
            .skill-list { padding-left: 10pt; margin: 0; }
            .skill-item { margin-bottom: 2pt; font-size: 9pt; }
            
            .clearfix { clear: both; }
        </style>

        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="name">' . htmlspecialchars(strtoupper($user->name)) . '</div>';
        
        if ($user->job_title) {
            $html .= '<div class="job-title">' . htmlspecialchars($user->job_title) . '</div>';
        }
        
        // Professional Summary (ATS-optimized - appears first)
        if ($summary && trim($summary)) {
            $html .= '<div class="summary">
                    <strong style="font-size: 11pt; display: block; margin-bottom: 5pt;">PROFESSIONAL SUMMARY</strong>
                    ' . nl2br(htmlspecialchars($summary)) . '
                </div>';
        } else if ($user->profile_summary && trim($user->profile_summary)) {
            $html .= '<div class="summary">
                    <strong style="font-size: 11pt; display: block; margin-bottom: 5pt;">PROFESSIONAL SUMMARY</strong>
                    ' . nl2br(htmlspecialchars($user->profile_summary)) . '
                </div>';
        }
        
        $html .= '</div>
            
            <!-- Main Column -->
            <div class="main-content">';

        // Technical Skills Section (ATS filters from here - MUST be before Experience)
        if ($user->skills->count() > 0) {
            $html .= '<div class="section-title">Technical Skills</div>';
            
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName && trim($categoryName)) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div style="margin-bottom: 5pt;">
                        <strong style="font-size: 10pt;">' . htmlspecialchars($categoryName) . ':</strong> 
                        <span style="font-size: 9.5pt;">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</span>
                    </div>';
                } else {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div style="margin-bottom: 5pt; font-size: 9.5pt;">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
                }
            }
        }

        // Business Skills (if applicable)
        if ($user->businessSkills->count() > 0) {
            $html .= '<div class="section-title">Business Skills</div>';
            
            $skillsByCategory = $user->businessSkills->groupBy('category.category_name');
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName && trim($categoryName)) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div style="margin-bottom: 5pt;">
                        <strong style="font-size: 10pt;">' . htmlspecialchars($categoryName) . ':</strong> 
                        <span style="font-size: 9.5pt;">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</span>
                    </div>';
                }
            }
        }

        // Engineering Skills (if applicable)
        if ($user->engineeringSkills->count() > 0) {
            $html .= '<div class="section-title">Engineering Skills</div>';
            
            $skillsByCategory = $user->engineeringSkills->groupBy('category.category_name');
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName && trim($categoryName)) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div style="margin-bottom: 5pt;">
                        <strong style="font-size: 10pt;">' . htmlspecialchars($categoryName) . ':</strong> 
                        <span style="font-size: 9.5pt;">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</span>
                    </div>';
                }
            }
        }

        // Medical Skills (if applicable)
        if ($user->medicalSkills->count() > 0) {
            $html .= '<div class="section-title">Medical Skills</div>';
            
            $skillsByCategory = $user->medicalSkills->groupBy('category.category_name');
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName && trim($categoryName)) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div style="margin-bottom: 5pt;">
                        <strong style="font-size: 10pt;">' . htmlspecialchars($categoryName) . ':</strong> 
                        <span style="font-size: 9.5pt;">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</span>
                    </div>';
                }
            }
        }

        // Work Experience Section
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section-title">Professional Experience</div>';

            // Sort experiences by date (newest first)
            $experiences = $user->experiences->sortByDesc(function($exp) {
                return $exp->start_date ? strtotime($exp->start_date) : 0;
            });

            foreach ($experiences as $exp) {
                $html .= '
                    <div class="item">
                        <div class="item-header">' . htmlspecialchars($exp->title) . '</div>';
                
                $companyLine = [];
                if ($exp->company && trim($exp->company)) $companyLine[] = htmlspecialchars($exp->company);
                if ($exp->location && trim($exp->location)) $companyLine[] = htmlspecialchars($exp->location);
                
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
                
                // Check if description exists (using description or responsibilities field)
                $description = $exp->description ?? $exp->responsibilities ?? '';
                if ($description && trim($description)) {
                    $responsibilities = explode("\n", $description);
                    $validItems = [];
                    foreach ($responsibilities as $res) {
                        $res = trim($res);
                        if (!empty($res)) {
                            $res = trim($res, "•- *");
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

        // Projects Section
        if ($user->projects->count() > 0) {
            $html .= '
                <div class="section-title">Projects</div>';
            
            foreach ($user->projects as $proj) {
                $html .= '
                    <div class="item">
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
                    $linkText = str_replace(['http://', 'https://'], '', $proj->link);
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">🔗 ' . htmlspecialchars($linkText) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }

        // Educational Background Section
        if ($education->count() > 0) {
            $html .= '<div class="section-title">Educational Background</div>';

            foreach ($education as $edu) {
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $university = $edu->university_name ?? $edu->institution ?? '';
                $field = $edu->field_of_study ?? '';
                
                if ($degree || $university) {
                    $html .= '
                        <div class="item">
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

        // Research Section (for Medical majors)
        if ($user->research->count() > 0 && $user->major === 'Medicine') {
            $html .= '
                <div class="section-title">Research</div>';
            
            foreach ($user->research as $res) {
                $html .= '
                    <div class="item">
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
                    $linkText = str_replace(['http://', 'https://'], '', $res->link);
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">🔗 ' . htmlspecialchars($linkText) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }

        // Activities Section
        if ($user->activities->count() > 0) {
            $html .= '
                <div class="section-title">Activities & Volunteer Work</div>';
            
            foreach ($user->activities as $act) {
                $html .= '
                    <div class="item">
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
                    $linkText = str_replace(['http://', 'https://'], '', $act->activity_link);
                    $html .= '<div style="font-size: 9pt; margin-top: 3pt;"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">🔗 ' . htmlspecialchars($linkText) . '</a></div>';
                }
                
                $html .= '</div>';
            }
        }

        $html .= '</div>

            <!-- Sidebar -->
            <div class="sidebar">';

        // Contact Section - only show if there's at least one contact info
        $hasContact = ($user->city && trim($user->city)) || 
                      ($user->phone && trim($user->phone)) || 
                      ($user->email && trim($user->email)) || 
                      ($user->profile_website && trim($user->profile_website)) ||
                      ($user->linkedin_profile && trim($user->linkedin_profile)) ||
                      ($user->github_profile && trim($user->github_profile));
        
        if ($hasContact) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Contact</div>';
            
            if ($user->city && trim($user->city)) {
                $html .= '<div class="contact-info">' . htmlspecialchars($user->city) . '</div>';
            }
            if ($user->phone && trim($user->phone)) {
                $html .= '<div class="contact-info">' . htmlspecialchars($user->phone) . '</div>';
            }
            if ($user->email && trim($user->email)) {
                $html .= '<div class="contact-info">✉️ ' . htmlspecialchars($user->email) . '</div>';
            }
            if ($user->profile_website && trim($user->profile_website)) {
                $link = $user->profile_website;
                if (!preg_match('/^https?:\/\//', $link)) {
                    $link = 'https://' . $link;
                }
                $linkText = str_replace(['http://', 'https://'], '', $user->profile_website);
                $html .= '<div class="contact-info"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">' . htmlspecialchars($linkText) . '</a></div>';
            }
            if ($user->linkedin_profile && trim($user->linkedin_profile)) {
                $link = $user->linkedin_profile;
                if (!preg_match('/^https?:\/\//', $link)) {
                    $link = 'https://' . $link;
                }
                $cleanLinkedin = str_replace(['https://', 'http://', 'www.', 'linkedin.com/in/'], '', $user->linkedin_profile);
                $cleanLinkedin = rtrim($cleanLinkedin, '/');
                $html .= '<div class="contact-info"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">LinkedIn: ' . htmlspecialchars($cleanLinkedin) . '</a></div>';
            }
            if ($user->github_profile && trim($user->github_profile)) {
                $link = $user->github_profile;
                if (!preg_match('/^https?:\/\//', $link)) {
                    $link = 'https://' . $link;
                }
                $cleanGithub = str_replace(['https://', 'http://', 'www.', 'github.com/'], '', $user->github_profile);
                $cleanGithub = rtrim($cleanGithub, '/');
                $html .= '<div class="contact-info"><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; text-decoration: none;">GitHub: ' . htmlspecialchars($cleanGithub) . '</a></div>';
            }
            
            $html .= '</div>';
        }

        // Skills Section - only show if there's at least one skill
        $hasSkills = $user->skills->count() > 0 || 
                     $user->businessSkills->count() > 0 || 
                     $user->engineeringSkills->count() > 0 || 
                     $user->medicalSkills->count() > 0 || 
                     ($user->analyticalSkills->count() > 0 && $user->major === 'IT') || 
                     $user->softSkills->count() > 0;
        
        if ($hasSkills) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Skills</div>';
        
        if ($user->skills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-bottom:3pt;">Technical Skills</div>
                      <ul class="skill-list">';
            foreach ($user->skills->take(10) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        // Business Skills
        if ($user->businessSkills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Business Skills</div>
                      <ul class="skill-list">';
            foreach ($user->businessSkills->take(8) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        // Engineering Skills
        if ($user->engineeringSkills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Engineering Skills</div>
                      <ul class="skill-list">';
            foreach ($user->engineeringSkills->take(8) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        // Medical Skills
        if ($user->medicalSkills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Medical Skills</div>
                      <ul class="skill-list">';
            foreach ($user->medicalSkills->take(8) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        // Analytical Skills
        if ($user->analyticalSkills->count() > 0 && $user->major === 'IT') {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Analytical Skills</div>
                      <ul class="skill-list">';
            foreach ($user->analyticalSkills->take(6) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        if ($user->softSkills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Soft Skills</div>
                      <ul class="skill-list">';
            foreach ($user->softSkills->take(6) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->soft_name) . '</li>';
            }
            $html .= '</ul>';
        }

            $html .= '</div>';
        }

        // Languages Section
        if ($user->languages->count() > 0) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Languages</div>
                    <ul class="skill-list">';
            foreach ($user->languages as $lang) {
                $proficiency = $lang->proficiency_level ?? $lang->proficiency ?? '';
                if ($proficiency) {
                    $html .= '<li class="skill-item">' . htmlspecialchars($lang->language_name) . ' (' . htmlspecialchars($proficiency) . ')</li>';
                } else {
                    $html .= '<li class="skill-item">' . htmlspecialchars($lang->language_name) . '</li>';
                }
            }
            $html .= '</ul>
                    </div>';
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
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Certifications</div>
                    <ul class="skill-list">';
            foreach ($user->certifications as $cert) {
                $certName = $cert->certifications_name ?? $cert->certificate_name ?? '';
                if ($certName && trim($certName)) {
                    $certText = htmlspecialchars($certName);
                    
                    if ($cert->issuing_org && trim($cert->issuing_org)) {
                        $certText .= ' - ' . htmlspecialchars($cert->issuing_org);
                    }
                    
                    if ($cert->issue_date) {
                        $issueDate = date('M Y', strtotime($cert->issue_date));
                        $certText .= ' (' . $issueDate;
                        if ($cert->expiration_date && $cert->expiration_date != '0000-00-00') {
                            $expDate = date('M Y', strtotime($cert->expiration_date));
                            $certText .= ' - ' . $expDate;
                        }
                        $certText .= ')';
                    }
                    
                    $html .= '<li class="skill-item" style="margin-bottom:4pt;">' . $certText;
                    
                    if ($cert->link_driver && trim($cert->link_driver)) {
                        $link = $cert->link_driver;
                        if (!preg_match('/^https?:\/\//', $link)) {
                            $link = 'https://' . $link;
                        }
                        $html .= '<br><a href="' . htmlspecialchars($link) . '" style="color: ' . $primaryColor . '; font-size: 8pt; text-decoration: none;">🔗 View Certificate</a>';
                    }
                    
                    $html .= '</li>';
                }
            }
            $html .= '</ul>
                    </div>';
        }

        // Professional Memberships Section
        if ($user->memberships->count() > 0) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Professional Memberships</div>
                    <ul class="skill-list">';
            foreach ($user->memberships as $m) {
                if ($m->organization_name && trim($m->organization_name)) {
                    $membershipText = htmlspecialchars($m->organization_name);
                    if ($m->membership_type && trim($m->membership_type)) {
                        $membershipText .= ' - ' . htmlspecialchars($m->membership_type);
                    }
                    
                    if ($m->start_date_membership || $m->end_date_membership) {
                        $startDate = $m->start_date_membership ? date('M Y', strtotime($m->start_date_membership)) : '';
                        $endDate = $m->end_date_membership ? date('M Y', strtotime($m->end_date_membership)) : 'Present';
                        if ($startDate || $endDate) {
                            $membershipText .= ' (' . $startDate . ' - ' . $endDate . ')';
                        }
                    }
                    
                    if ($m->membership_status && trim($m->membership_status)) {
                        $membershipText .= ' [' . htmlspecialchars($m->membership_status) . ']';
                    }
                    
                    $html .= '<li class="skill-item" style="margin-bottom:4pt;">' . $membershipText . '</li>';
                }
            }
            $html .= '</ul>
                    </div>';
        }

        // Core Competencies Section
        if ($user->coreCompetencies->count() > 0) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Core Competencies</div>
                    <ul class="skill-list">';
            foreach ($user->coreCompetencies as $comp) {
                if ($comp->competency_name && trim($comp->competency_name)) {
                    $compText = htmlspecialchars($comp->competency_name);
                    if ($comp->description && trim($comp->description)) {
                        $compText .= ' - ' . htmlspecialchars(substr(trim($comp->description), 0, 50));
                    }
                    $html .= '<li class="skill-item" style="margin-bottom:4pt;">' . $compText . '</li>';
                }
            }
            $html .= '</ul>
                    </div>';
        }

        // Interests Section
        if ($user->interests->count() > 0) {
            $html .= '<div class="sidebar-section">
                    <div class="sidebar-title">Interests</div>
                    <ul class="skill-list">';
            foreach ($user->interests as $interest) {
                if ($interest->interest_name && trim($interest->interest_name)) {
                    $html .= '<li class="skill-item">' . htmlspecialchars($interest->interest_name) . '</li>';
                }
            }
            $html .= '</ul>
                    </div>';
        }
        
        $html .= '</div>
            
            <div class="clearfix"></div>
        </div>';

        return $html;
    }
}
