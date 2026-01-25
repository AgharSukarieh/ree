<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\ListItem;
use Mpdf\Mpdf;

class DownloadControllerStayleThree extends \App\Http\Controllers\Controller
{
    /**
     * Generate and download PDF CV (ATS-optimized)
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
                ->orderBy('end_year', 'desc')
                ->orderBy('start_year', 'desc')
                ->get();
        } catch (\Exception $e) {
            $education = collect([]);
        }

        // Generate optimized professional summary
        $summary = $this->generateProfessionalSummary($user, $education);

        // Prepare ATS-friendly HTML
        $html = $this->buildATSHtml($user, $education, $summary);

        // Generate PDF
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
                'allow_charset_conversion' => true
            ]);
            
            // Enable links in PDF
            $mpdf->SetHTMLFooter('');
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            
            $mpdf->WriteHTML($html);
            $mpdf->Output('CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.pdf', 'D');
            exit;
        } catch (\Exception $e) {
            abort(500, 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate optimized professional summary
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
     * Build ATS-optimized HTML
     */
    private function buildATSHtml($user, $education, $summary)
    {
        // --- NEW ATS-Optimized CSS Styles (Matching the new design) ---
        $html = '<style>
            body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; line-height: 1.5; color: #000000; margin: 0; padding: 0; }
            h1 { font-size: 24pt; font-weight: bold; color: #000000; margin: 0 0 0px 0; text-align: left; } /* Larger name */
            h2 { 
                font-size: 14pt; 
                font-weight: bold; 
                color: #000000; 
                margin: 20px 0 10px 0; 
                text-transform: uppercase; 
                background-color: #eeeeee; /* Light gray background */
                padding: 5px 10px; /* Padding for the background */
                line-height: 1.2;
            }
            h3 { font-size: 11pt; font-weight: bold; color: #000000; margin: 10px 0 4px 0; }
            .header { text-align: left; margin-bottom: 15px; }
            .contact { font-size: 10pt; margin: 5px 0; line-height: 1.6; }
            .section { margin: 12px 0; }
            .item { margin: 8px 0; }
            .job-title { font-weight: bold; font-size: 11pt; }
            .company { font-weight: normal; }
            .date { font-size: 10pt; color: #333333; font-style: italic; text-align: right; }
            .bullet { margin: 3px 0; padding-left: 15px; }
            .skills-inline { margin: 5px 0; line-height: 1.6; }
            .skill-category { font-weight: bold; margin-top: 6px; }
            p { margin: 4px 0; line-height: 1.5; }
            ul { margin: 5px 0; padding-left: 20px; }
            li { margin: 2px 0; line-height: 1.4; }
            a { color: #666666; text-decoration: underline; }
            a:visited { color: #666666; }
            .experience-header, .education-header { display: table; width: 100%; margin-bottom: 5px; }
            .exp-title-company, .exp-date { display: table-cell; }
            .exp-date { text-align: right; width: 30%; }
        </style>';

        // --- Header Section (Matching the new design) ---
        $html .= '<div class="header">';
        $html .= '<h1>' . htmlspecialchars(strtoupper($user->name)) . '</h1>';
        
        if ($user->job_title) {
            $html .= '<div style="font-size: 14pt; font-weight: bold; margin-bottom: 10px;">' . htmlspecialchars(strtoupper($user->job_title)) . '</div>';
        }
        
        // Contact information (single line, with clickable links)
        $contact = [];
        if ($user->city) $contact[] = htmlspecialchars($user->city);
        if ($user->phone) $contact[] = htmlspecialchars($user->phone);
        if ($user->email) {
            $contact[] = '<a href="mailto:' . htmlspecialchars($user->email) . '" style="color: #000000; text-decoration: none;">' . htmlspecialchars($user->email) . '</a>';
        }
        
        // Profile page link
        $profileUrl = url('/profile/' . $user->qr_id);
        $contact[] = '<a href="' . htmlspecialchars($profileUrl) . '" style="color: #000000; text-decoration: none;">View Profile</a>';
        
        if ($user->profile_website) {
            $link = $user->profile_website;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://'], '', $user->profile_website);
            $contact[] = '<a href="' . htmlspecialchars($link) . '" style="color: #000000; text-decoration: none;">' . htmlspecialchars($linkText) . '</a>';
        }
        
        // Social profiles (merged into contact line for simplicity)
        if ($user->linkedin_profile) {
            $link = $user->linkedin_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://', 'www.linkedin.com/in/'], '', $user->linkedin_profile);
            $contact[] = 'LinkedIn: <a href="' . htmlspecialchars($link) . '" style="color: #000000; text-decoration: none;">' . htmlspecialchars($linkText) . '</a>';
        }
        if ($user->github_profile) {
            $link = $user->github_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://', 'www.github.com/'], '', $user->github_profile);
            $contact[] = 'GitHub: <a href="' . htmlspecialchars($link) . '" style="color: #000000; text-decoration: none;">' . htmlspecialchars($linkText) . '</a>';
        }

        if (!empty($contact)) {
            $html .= '<div class="contact">' . implode(' | ', $contact) . '</div>';
        }
        
        $html .= '</div>';

        // 1. Professional Summary
        if ($summary) {
            $html .= '<div class="section">';
            $html .= '<h2>SUMMARY</h2>';
            $html .= '<p>' . nl2br(htmlspecialchars($summary)) . '</p>';
            $html .= '</div>';
        }

        // 2. Technical Skills (ATS filters from here - MUST be before Experience)
        if ($user->skills->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>TECHNICAL SKILLS</h2>';
            
            // Group skills by category
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            
            // Use a table for a multi-column layout (safer for Mpdf)
            $html .= '<table style="width: 100%; border-collapse: collapse; font-size: 10pt;">';
            $html .= '<tr>';
            
            $colCount = 0;
            foreach ($skillsByCategory as $categoryName => $skills) {
                $skillNames = $skills->pluck('skill_name')->toArray();
                
                // Start a new row every 3 columns
                if ($colCount > 0 && $colCount % 3 == 0) {
                    $html .= '</tr><tr>';
                }
                
                $html .= '<td style="width: 33%; padding-right: 10px; vertical-align: top;">';
                
                if ($categoryName) {
                    $html .= '<div class="skill-category">' . htmlspecialchars($categoryName) . '</div>';
                }
                
                $html .= '<ul>';
                foreach ($skillNames as $skill) {
                    $html .= '<li>' . htmlspecialchars($skill) . '</li>';
                }
                $html .= '</ul>';
                
                $html .= '</td>';
                $colCount++;
            }
            
            // Fill remaining columns if needed
            while ($colCount % 3 != 0) {
                $html .= '<td style="width: 33%;"></td>';
                $colCount++;
            }
            
            $html .= '</tr>';
            $html .= '</table>';
            
            $html .= '</div>';
        }

        // 3. Work Experience (Most important section for HR)
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>PROFESSIONAL EXPERIENCE</h2>';
            
            foreach ($user->experiences as $exp) {
                $html .= '<div class="item">';
                
                // Title, Company, and Date in a structured header
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $dateRange = $startDate . ($startDate && $endDate ? ' - ' : '') . $endDate;

                $html .= '<div class="experience-header">';
                $html .= '<div class="exp-title-company">';
                $html .= '<span class="job-title">' . htmlspecialchars($exp->title) . '</span>, ';
                $html .= '<span class="company">' . htmlspecialchars($exp->company) . '</span>';
                $html .= '</div>';
                $html .= '<div class="exp-date">' . htmlspecialchars($dateRange) . '</div>';
                $html .= '</div>';
                
                // Location (optional)
                if ($exp->location) {
                    $html .= '<div style="font-size: 10pt; margin-bottom: 5px;">' . htmlspecialchars($exp->location) . '</div>';
                }

                // Description (Bullet Points)
                if ($exp->description && trim($exp->description)) {
                    $bullets = $this->formatBulletPoints($exp->description);
                    if (!empty($bullets)) {
                        $html .= '<ul>';
                        foreach ($bullets as $bullet) {
                            $bullet = trim($bullet);
                            if (!empty($bullet)) {
                                $html .= '<li>' . htmlspecialchars($bullet) . '</li>';
                            }
                        }
                        $html .= '</ul>';
                    }
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 4. Projects (If no experience, or for portfolio/tech roles)
        if ($user->projects->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>KEY PROJECTS</h2>';
            
            foreach ($user->projects as $project) {
                $html .= '<div class="item">';
                $html .= '<div class="job-title">' . htmlspecialchars($project->project_name) . '</div>';
                
                if ($project->project_link) {
                    $link = $project->project_link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $linkText = str_replace(['http://', 'https://'], '', $project->project_link);
                    $html .= '<div style="font-size: 10pt; margin-bottom: 5px;"><a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a></div>';
                }
                
                if ($project->description_project && trim($project->description_project)) {
                    $bullets = $this->formatBulletPoints($project->description_project);
                    if (!empty($bullets)) {
                        $html .= '<ul>';
                        foreach ($bullets as $bullet) {
                            $bullet = trim($bullet);
                            if (!empty($bullet)) {
                                $html .= '<li>' . htmlspecialchars($bullet) . '</li>';
                            }
                        }
                        $html .= '</ul>';
                    }
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 5. Education
        if ($education->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>EDUCATION</h2>';
            
            foreach ($education as $edu) {
                $html .= '<div class="item">';
                
                $startDate = $edu->start_year ? date('M Y', strtotime($edu->start_year . '-01-01')) : '';
                $endDate = $edu->end_year ? date('M Y', strtotime($edu->end_year . '-01-01')) : 'Present';
                $dateRange = $startDate . ($startDate && $endDate ? ' - ' : '') . $endDate;

                $html .= '<div class="education-header">';
                $html .= '<div class="exp-title-company">';
                $html .= '<span class="job-title">' . htmlspecialchars($edu->degree) . '</span>, ';
                $html .= '<span class="company">' . htmlspecialchars($edu->university) . '</span>';
                $html .= '</div>';
                $html .= '<div class="exp-date">' . htmlspecialchars($dateRange) . '</div>';
                $html .= '</div>';
                
                // Major/Thesis/Notes
                $notes = [];
                if ($edu->major) {
                    $notes[] = 'Major in ' . htmlspecialchars($edu->major);
                }
                if ($edu->thesis) {
                    $notes[] = 'Thesis on "' . htmlspecialchars($edu->thesis) . '"';
                }
                if ($edu->notes) {
                    $notes[] = htmlspecialchars($edu->notes);
                }
                
                if (!empty($notes)) {
                    $html .= '<ul>';
                    foreach ($notes as $note) {
                        $html .= '<li>' . $note . '</li>';
                    }
                    $html .= '</ul>';
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 6. Certifications
        if ($user->certifications->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>CERTIFICATIONS</h2>';
            
            $html .= '<ul>';
            foreach ($user->certifications as $cert) {
                $certText = htmlspecialchars($cert->certifications_name);
                if ($cert->issuing_org) {
                    $certText .= ' - ' . htmlspecialchars($cert->issuing_org);
                }
                
                $issueDate = $cert->issue_date ? date('M Y', strtotime($cert->issue_date)) : '';
                $expiryDate = $cert->expiration_date ? date('M Y', strtotime($cert->expiration_date)) : '';
                
                if ($issueDate) {
                    $certText .= ', ' . $issueDate;
                    if ($expiryDate && $expiryDate != $issueDate) {
                        $certText .= ' - ' . $expiryDate;
                    }
                }
                
                $html .= '<li>' . $certText . '</li>';
            }
            $html .= '</ul>';
            
            $html .= '</div>';
        }

        // 7. Additional Information (Languages, Soft Skills, Activities, Memberships)
        $additionalInfo = [];
        
        // Languages
        if ($user->languages->count() > 0) {
            $langList = $user->languages->map(function($lang) {
                return htmlspecialchars($lang->language_name) . ': ' . htmlspecialchars($lang->proficiency_level);
            })->implode(', ');
            $additionalInfo[] = 'Languages: ' . $langList;
        }

        // Soft Skills
        if ($user->softSkills->count() >= 3) {
            $softList = $user->softSkills->pluck('soft_name')->map('htmlspecialchars')->implode(', ');
            $additionalInfo[] = 'Soft Skills: ' . $softList;
        }

        // Activities & Memberships (Combined)
        $activities = [];
        foreach ($user->activities as $act) {
            $activities[] = htmlspecialchars($act->activity_title) . ' (' . htmlspecialchars($act->organization) . ')';
        }
        foreach ($user->memberships as $m) {
            $activities[] = htmlspecialchars($m->organization_name) . ' (' . htmlspecialchars($m->membership_type) . ')';
        }
        if (!empty($activities)) {
            $additionalInfo[] = 'Activities/Memberships: ' . implode(', ', $activities);
        }

        // Research (Medical major only)
        if ($user->research->count() > 0 && $user->major === 'Medicine') {
            $researchList = $user->research->pluck('title')->map('htmlspecialchars')->implode(', ');
            $additionalInfo[] = 'Research/Publications: ' . $researchList;
        }

        if (!empty($additionalInfo)) {
            $html .= '<div class="section">';
            $html .= '<h2>ADDITIONAL INFORMATION</h2>';
            
            $html .= '<ul>';
            foreach ($additionalInfo as $info) {
                $html .= '<li>' . $info . '</li>';
            }
            $html .= '</ul>';
            
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Format bullet points for ATS (STAR method enhancement)
     */
    private function formatBulletPoints($text)
    {
        if (empty($text) || !trim($text)) {
            return [];
        }
        
        $lines = explode("\n", $text);
        $bullets = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Remove existing bullets
            $line = preg_replace('/^[-•*]\s*/', '', $line);
            $line = trim($line);
            
            // Skip if empty after removing bullets
            if (empty($line)) continue;
            
            // Enhance with action verbs if needed (This is crucial for ATS)
            $line = $this->enhanceBulletPoint($line);
            $line = trim($line);
            
            // Only add if not empty after enhancement
            if (!empty($line)) {
                $bullets[] = $line;
            }
        }
        
        return $bullets;
    }

    /**
     * Enhance bullet point with action verbs and quantification
     */
    private function enhanceBulletPoint($text)
    {
        // Common weak starts to replace
        $weakStarts = [
            '/^worked on/i' => 'Developed',
            '/^responsible for/i' => 'Managed',
            '/^helped with/i' => 'Contributed to',
            '/^did/i' => 'Executed',
            '/^made/i' => 'Created',
            '/^used/i' => 'Implemented',
        ];
        
        foreach ($weakStarts as $pattern => $replacement) {
            if (preg_match($pattern, $text)) {
                $text = preg_replace($pattern, $replacement, $text);
                break;
            }
        }
        
        // Ensure it starts with capital letter
        $text = ucfirst(trim($text));
        
        // Ensure it ends with period if it's a complete sentence
        if (strlen($text) > 20 && !preg_match('/[.!?]$/', $text)) {
            $text .= '.';
        }
        
        return $text;
    }

    /**
     * Generate and download Word CV (ATS-optimized)
     */
    public function generateWord($qr_id)
    {
        try {
            $user = User::with([
                'activities',
                'analyticalSkills',
                'businessSkills.category',
                'certifications',
                'coreCompetencies',
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

        // Fetch education
        try {
            $education = DB::table('education')
                ->where('qr_id', $qr_id)
                ->orderBy('end_year', 'desc')
                ->orderBy('start_year', 'desc')
                ->get();
        } catch (\Exception $e) {
            $education = collect([]);
        }

        // Generate summary
        $summary = $this->generateProfessionalSummary($user, $education);

        // Build Word document
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'marginTop' => 720,
            'marginBottom' => 720,
            'marginLeft' => 720,
            'marginRight' => 720
        ]);

        // --- Styles for Word Document (Matching the new design) ---
        $phpWord->addFontStyle('Header1', ['size' => 24, 'bold' => true]);
        $phpWord->addFontStyle('Header2', ['size' => 14, 'bold' => true, 'bgColor' => 'EEEEEE']); // Gray background
        $phpWord->addParagraphStyle('Header2Style', ['spaceAfter' => 120, 'alignment' => Jc::LEFT, 'shading' => ['fill' => 'EEEEEE', 'val' => 'clear']]);
        $phpWord->addFontStyle('JobTitle', ['size' => 14, 'bold' => true]);
        $phpWord->addFontStyle('ContactInfo', ['size' => 10]);
        $phpWord->addFontStyle('ExpTitle', ['size' => 11, 'bold' => true]);
        $phpWord->addFontStyle('ExpDate', ['size' => 10, 'italic' => true]);
        $phpWord->addParagraphStyle('ExpHeaderStyle', ['spaceAfter' => 60]);
        $phpWord->addParagraphStyle('BulletStyle', ['listType' => ListItem::TYPE_BULLET_FILLED, 'spaceAfter' => 60]);
        $phpWord->addParagraphStyle('SkillListStyle', ['spaceAfter' => 60]);


        // Header
        $section->addText(
            strtoupper(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8')),
            'Header1',
            ['alignment' => Jc::LEFT, 'spaceAfter' => 0]
        );

        if ($user->job_title) {
            $section->addText(
                strtoupper(htmlspecialchars($user->job_title, ENT_QUOTES, 'UTF-8')),
                'JobTitle',
                ['alignment' => Jc::LEFT, 'spaceAfter' => 120]
            );
        }

        // Contact
        $contact = [];
        if ($user->city) $contact[] = htmlspecialchars($user->city);
        if ($user->phone) $contact[] = htmlspecialchars($user->phone);
        if ($user->email) $contact[] = htmlspecialchars($user->email);
        if ($user->profile_website) {
            $link = str_replace(['http://', 'https://'], '', $user->profile_website);
            $contact[] = htmlspecialchars($link);
        }
        if ($user->linkedin_profile) {
            $link = str_replace(['http://', 'https://', 'www.linkedin.com/in/'], '', $user->linkedin_profile);
            $contact[] = 'LinkedIn: ' . htmlspecialchars($link);
        }
        if ($user->github_profile) {
            $link = str_replace(['http://', 'https://', 'www.github.com/'], '', $user->github_profile);
            $contact[] = 'GitHub: ' . htmlspecialchars($link);
        }

        if (!empty($contact)) {
            $section->addText(
                implode(' | ', $contact),
                'ContactInfo',
                ['alignment' => Jc::LEFT, 'spaceAfter' => 240]
            );
        }

        // Helper function to add section header with gray background
        $addSectionHeader = function($title) use ($section) {
            $section->addText($title, 'Header2', 'Header2Style');
        };

        // 1. Professional Summary
        if ($summary) {
            $addSectionHeader('SUMMARY');
            $section->addText(htmlspecialchars($summary, ENT_QUOTES, 'UTF-8'), [], ['spaceAfter' => 240]);
        }

        // 2. Technical Skills
        if ($user->skills->count() > 0) {
            $addSectionHeader('TECHNICAL SKILLS');
            
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            
            // Use a table for a multi-column layout (2 columns for simplicity in Word)
            $table = $section->addTable(['width' => 10000, 'unit' => TblWidth::TWIP, 'cellMargin' => 100]);
            $table->addRow();
            
            $colCount = 0;
            foreach ($skillsByCategory as $categoryName => $skills) {
                $skillNames = $skills->pluck('skill_name')->toArray();
                
                // Start a new row every 2 columns
                if ($colCount > 0 && $colCount % 2 == 0) {
                    $table->addRow();
                }
                
                $cell = $table->addCell(5000); // 50% width
                
                if ($categoryName) {
                    $cell->addText(htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'), ['bold' => true], 'SkillListStyle');
                }
                
                foreach ($skillNames as $skill) {
                    $cell->addListItem(htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'), 0, [], 'BulletStyle');
                }
                
                $colCount++;
            }
            
            $section->addText('', [], ['spaceAfter' => 120]);
        }

        // 3. Work Experience
        if ($user->experiences->count() > 0) {
            $addSectionHeader('PROFESSIONAL EXPERIENCE');
            
            foreach ($user->experiences as $exp) {
                // Title, Company, and Date in a structured header
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $dateRange = $startDate . ($startDate && $endDate ? ' - ' : '') . $endDate;

                $table = $section->addTable(['width' => 10000, 'unit' => TblWidth::TWIP, 'cellMargin' => 0]);
                $table->addRow();
                
                // Title and Company (Left)
                $cellLeft = $table->addCell(7000);
                $cellLeft->addText(
                    htmlspecialchars($exp->title, ENT_QUOTES, 'UTF-8') . ', ' . htmlspecialchars($exp->company, ENT_QUOTES, 'UTF-8'),
                    'ExpTitle',
                    ['spaceAfter' => 0]
                );

                // Date Range (Right)
                $cellRight = $table->addCell(3000, ['alignment' => Jc::RIGHT]);
                $cellRight->addText(
                    htmlspecialchars($dateRange, ENT_QUOTES, 'UTF-8'),
                    'ExpDate',
                    ['alignment' => Jc::RIGHT, 'spaceAfter' => 0]
                );
                
                // Location (optional)
                if ($exp->location) {
                    $section->addText(htmlspecialchars($exp->location, ENT_QUOTES, 'UTF-8'), ['size' => 10], ['spaceAfter' => 60]);
                }

                // Description (Bullet Points)
                if ($exp->description && trim($exp->description)) {
                    $bullets = $this->formatBulletPoints($exp->description);
                    if (!empty($bullets)) {
                        foreach ($bullets as $bullet) {
                            $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0, [], 'BulletStyle');
                        }
                    }
                }
                
                $section->addText('', [], ['spaceAfter' => 120]); // Spacer
            }
        }

        // 4. Projects
        if ($user->projects->count() > 0) {
            $addSectionHeader('KEY PROJECTS');
            
            foreach ($user->projects as $project) {
                $section->addText(htmlspecialchars($project->project_name, ENT_QUOTES, 'UTF-8'), 'ExpTitle', ['spaceAfter' => 60]);
                
                if ($project->project_link) {
                    $link = $project->project_link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $linkText = str_replace(['http://', 'https://'], '', $project->project_link);
                    $section->addLink($link, htmlspecialchars($linkText, ENT_QUOTES, 'UTF-8'), ['size' => 10], ['spaceAfter' => 60]);
                }
                
                if ($project->description_project && trim($project->description_project)) {
                    $bullets = $this->formatBulletPoints($project->description_project);
                    if (!empty($bullets)) {
                        foreach ($bullets as $bullet) {
                            $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0, [], 'BulletStyle');
                        }
                    }
                }
                
                $section->addText('', [], ['spaceAfter' => 120]); // Spacer
            }
        }

        // 5. Education
        if ($education->count() > 0) {
            $addSectionHeader('EDUCATION');
            
            foreach ($education as $edu) {
                $startDate = $edu->start_year ? date('M Y', strtotime($edu->start_year . '-01-01')) : '';
                $endDate = $edu->end_year ? date('M Y', strtotime($edu->end_year . '-01-01')) : 'Present';
                $dateRange = $startDate . ($startDate && $endDate ? ' - ' : '') . $endDate;

                $table = $section->addTable(['width' => 10000, 'unit' => TblWidth::TWIP, 'cellMargin' => 0]);
                $table->addRow();
                
                // Degree and University (Left)
                $cellLeft = $table->addCell(7000);
                $cellLeft->addText(
                    htmlspecialchars($edu->degree, ENT_QUOTES, 'UTF-8') . ', ' . htmlspecialchars($edu->university, ENT_QUOTES, 'UTF-8'),
                    'ExpTitle',
                    ['spaceAfter' => 0]
                );

                // Date Range (Right)
                $cellRight = $table->addCell(3000, ['alignment' => Jc::RIGHT]);
                $cellRight->addText(
                    htmlspecialchars($dateRange, ENT_QUOTES, 'UTF-8'),
                    'ExpDate',
                    ['alignment' => Jc::RIGHT, 'spaceAfter' => 0]
                );
                
                // Major/Thesis/Notes
                $notes = [];
                if ($edu->major) {
                    $notes[] = 'Major in ' . htmlspecialchars($edu->major);
                }
                if ($edu->thesis) {
                    $notes[] = 'Thesis on "' . htmlspecialchars($edu->thesis) . '"';
                }
                if ($edu->notes) {
                    $notes[] = htmlspecialchars($edu->notes);
                }
                
                if (!empty($notes)) {
                    foreach ($notes as $note) {
                        $section->addListItem($note, 0, [], 'BulletStyle');
                    }
                }
                
                $section->addText('', [], ['spaceAfter' => 120]); // Spacer
            }
        }

        // 6. Certifications
        if ($user->certifications->count() > 0) {
            $addSectionHeader('CERTIFICATIONS');
            
            foreach ($user->certifications as $cert) {
                $certText = htmlspecialchars($cert->certifications_name);
                if ($cert->issuing_org) {
                    $certText .= ' - ' . htmlspecialchars($cert->issuing_org);
                }
                
                $issueDate = $cert->issue_date ? date('M Y', strtotime($cert->issue_date)) : '';
                $expiryDate = $cert->expiration_date ? date('M Y', strtotime($cert->expiration_date)) : '';
                
                if ($issueDate) {
                    $certText .= ', ' . $issueDate;
                    if ($expiryDate && $expiryDate != $issueDate) {
                        $certText .= ' - ' . $expiryDate;
                    }
                }
                
                $section->addListItem($certText, 0, [], 'BulletStyle');
            }
            
            $section->addText('', [], ['spaceAfter' => 120]); // Spacer
        }

        // 7. Additional Information
        $additionalInfo = [];
        
        // Languages
        if ($user->languages->count() > 0) {
            $langList = $user->languages->map(function($lang) {
                return htmlspecialchars($lang->language_name) . ': ' . htmlspecialchars($lang->proficiency_level);
            })->implode(', ');
            $additionalInfo[] = 'Languages: ' . $langList;
        }

        // Soft Skills
        if ($user->softSkills->count() >= 3) {
            $softList = $user->softSkills->pluck('soft_name')->map('htmlspecialchars')->implode(', ');
            $additionalInfo[] = 'Soft Skills: ' . $softList;
        }

        // Activities & Memberships (Combined)
        $activities = [];
        foreach ($user->activities as $act) {
            $activities[] = htmlspecialchars($act->activity_title) . ' (' . htmlspecialchars($act->organization) . ')';
        }
        foreach ($user->memberships as $m) {
            $activities[] = htmlspecialchars($m->organization_name) . ' (' . htmlspecialchars($m->membership_type) . ')';
        }
        if (!empty($activities)) {
            $additionalInfo[] = 'Activities/Memberships: ' . implode(', ', $activities);
        }

        // Research (Medical major only)
        if ($user->research->count() > 0 && $user->major === 'Medicine') {
            $researchList = $user->research->pluck('title')->map('htmlspecialchars')->implode(', ');
            $additionalInfo[] = 'Research/Publications: ' . $researchList;
        }

        if (!empty($additionalInfo)) {
            $addSectionHeader('ADDITIONAL INFORMATION');
            
            foreach ($additionalInfo as $info) {
                $section->addListItem($info, 0, [], 'BulletStyle');
            }
            
            $section->addText('', [], ['spaceAfter' => 120]); // Spacer
        }

        // Save Word document
        $fileName = 'CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter->save('php://output');
        exit;
    }
}
