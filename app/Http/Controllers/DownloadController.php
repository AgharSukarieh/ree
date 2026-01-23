<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\ListItem;
use Mpdf\Mpdf;

class DownloadController extends Controller
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
        $html = '<style>
            body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; line-height: 1.5; color: #000000; margin: 0; padding: 0; }
            h1 { font-size: 18pt; font-weight: bold; color: #000000; margin: 0 0 3px 0; text-align: center; }
            h2 { font-size: 12pt; font-weight: bold; color: #000000; margin: 15px 0 8px 0; text-transform: uppercase; border-bottom: 1px solid #000000; padding-bottom: 2px; }
            h3 { font-size: 11pt; font-weight: bold; color: #000000; margin: 10px 0 4px 0; }
            .header { text-align: center; margin-bottom: 12px; }
            .contact { text-align: center; font-size: 10pt; margin: 5px 0; line-height: 1.6; }
            .section { margin: 12px 0; }
            .item { margin: 8px 0; }
            .job-title { font-weight: bold; font-size: 11pt; }
            .company { font-weight: bold; }
            .date { font-size: 10pt; color: #333333; font-style: italic; }
            .bullet { margin: 3px 0; padding-left: 15px; }
            .skills-inline { margin: 5px 0; line-height: 1.6; }
            .skill-category { font-weight: bold; margin-top: 6px; }
            p { margin: 4px 0; line-height: 1.5; }
            ul { margin: 5px 0; padding-left: 20px; }
            li { margin: 2px 0; line-height: 1.4; }
            a { color: #666666; text-decoration: underline; }
            a:visited { color: #666666; }
        </style>';

        // Header
        $html .= '<div class="header">';
        $html .= '<h1>' . htmlspecialchars(strtoupper($user->name)) . '</h1>';
        
        if ($user->job_title) {
            $html .= '<div style="font-size: 11pt; margin-bottom: 5px;">' . htmlspecialchars($user->job_title) . '</div>';
        }
        
        // Contact information (single line, with clickable links)
        $contact = [];
        if ($user->city) $contact[] = htmlspecialchars($user->city);
        if ($user->phone) $contact[] = htmlspecialchars($user->phone);
        if ($user->email) {
            $contact[] = '<a href="mailto:' . htmlspecialchars($user->email) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($user->email) . '</a>';
        }
        
        // Profile page link
        $profileUrl = url('/profile/' . $user->qr_id);
        $contact[] = '<a href="' . htmlspecialchars($profileUrl) . '" style="color: #666666; text-decoration: underline;">View Profile</a>';
        
        if ($user->profile_website) {
            $link = $user->profile_website;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://'], '', $user->profile_website);
            $contact[] = '<a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a>';
        }
        
        if (!empty($contact)) {
            $html .= '<div class="contact">' . implode(' | ', $contact) . '</div>';
        }
        
        // Social profiles (clickable links)
        $social = [];
        if ($user->linkedin_profile) {
            $link = $user->linkedin_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://', 'www.linkedin.com/in/'], '', $user->linkedin_profile);
            $social[] = 'LinkedIn: <a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a>';
        }
        if ($user->github_profile) {
            $link = $user->github_profile;
            if (!preg_match('/^https?:\/\//', $link)) {
                $link = 'https://' . $link;
            }
            $linkText = str_replace(['http://', 'https://', 'www.github.com/'], '', $user->github_profile);
            $social[] = 'GitHub: <a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a>';
        }
        
        if (!empty($social)) {
            $html .= '<div class="contact">' . implode(' | ', $social) . '</div>';
        }
        
        $html .= '</div>';

        // 1. Professional Summary (First thing HR reads)
        if ($summary) {
            $html .= '<div class="section">';
            $html .= '<h2>PROFESSIONAL SUMMARY</h2>';
            $html .= '<p>' . nl2br(htmlspecialchars($summary)) . '</p>';
            $html .= '</div>';
        }

        // 2. Technical Skills (ATS filters from here - MUST be before Experience)
        // Technical Skills (grouped by category for ATS keyword scanning)
        if ($user->skills->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>TECHNICAL SKILLS</h2>';
            
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            $skillLines = [];
            
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = '<strong>' . htmlspecialchars($categoryName) . ':</strong> ' . 
                                   implode(', ', array_map('htmlspecialchars', $skillNames));
                } else {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = implode(', ', array_map('htmlspecialchars', $skillNames));
                }
            }
            
            $html .= '<div class="skills-inline">' . implode('<br>', $skillLines) . '</div>';
            $html .= '</div>';
        }

        // Medical Skills (if applicable)
        if ($user->medicalSkills->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>MEDICAL SKILLS</h2>';
            
            $skillsByCategory = $user->medicalSkills->groupBy('category.category_name');
            $skillLines = [];
            
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = '<strong>' . htmlspecialchars($categoryName) . ':</strong> ' . 
                                   implode(', ', array_map('htmlspecialchars', $skillNames));
                } else {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = implode(', ', array_map('htmlspecialchars', $skillNames));
                }
            }
            
            $html .= '<div class="skills-inline">' . implode('<br>', $skillLines) . '</div>';
            $html .= '</div>';
        }

        // 3. Work Experience (Most important section for HR)
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>PROFESSIONAL EXPERIENCE</h2>';
            
            $experiences = $user->experiences->sortByDesc(function($exp) {
                return $exp->start_date ? strtotime($exp->start_date) : 0;
            });
            
            foreach ($experiences as $exp) {
                $html .= '<div class="item">';
                $html .= '<div class="job-title">' . htmlspecialchars($exp->title) . '</div>';
                
                $companyLine = [];
                if ($exp->company) $companyLine[] = htmlspecialchars($exp->company);
                if ($exp->location) $companyLine[] = htmlspecialchars($exp->location);
                
                if (!empty($companyLine)) {
                    $html .= '<div class="company">' . implode(' - ', $companyLine) . '</div>';
                }
                
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $html .= '<div class="date">' . $startDate . ' - ' . $endDate;
                if ($exp->is_internship) {
                    $html .= ' (Internship)';
                }
                $html .= '</div>';
                
                if ($exp->description) {
                    $bullets = $this->formatBulletPoints($exp->description);
                    foreach ($bullets as $bullet) {
                        $html .= '<div class="bullet">• ' . htmlspecialchars($bullet) . '</div>';
                    }
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 4. Projects (Especially important for Tech/Fresh/Juniors)
        // Projects (if applicable and substantial)
        if ($user->projects->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>PROJECTS</h2>';
            
            foreach ($user->projects as $proj) {
                $html .= '<div class="item">';
                $html .= '<div class="job-title">' . htmlspecialchars($proj->project_title) . '</div>';
                
                if ($proj->description) {
                    $bullets = $this->formatBulletPoints($proj->description);
                    foreach ($bullets as $bullet) {
                        $html .= '<div class="bullet">• ' . htmlspecialchars($bullet) . '</div>';
                    }
                }
                
                if ($proj->technologies_used) {
                    $html .= '<div style="margin-top: 4px; font-size: 10pt;"><strong>Technologies:</strong> ' . 
                            htmlspecialchars($proj->technologies_used) . '</div>';
                }
                
                if ($proj->link) {
                    $link = $proj->link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $linkText = str_replace(['http://', 'https://'], '', $proj->link);
                    $html .= '<div style="font-size: 10pt;"><a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a></div>';
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
                
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $field = $edu->field_of_study ?? '';
                $university = $edu->university_name ?? $edu->university ?? '';
                
                if ($degree) {
                    $degreeText = htmlspecialchars($degree);
                    if ($field) {
                        $degreeText .= ' in ' . htmlspecialchars($field);
                    }
                    $html .= '<div class="job-title">' . $degreeText . '</div>';
                }
                
                if ($university) {
                    $html .= '<div>' . htmlspecialchars($university) . '</div>';
                }
                
                $startYear = $edu->start_year ?? '';
                $endYear = ($edu->end_year ?? 0) == 0 ? 'Present' : ($edu->end_year ?? '');
                if ($startYear || $endYear) {
                    $html .= '<div class="date">' . $startYear . ' - ' . $endYear . '</div>';
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 6. Certifications (Supporting, not essential)
        if ($user->certifications->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>CERTIFICATIONS</h2>';
            
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
                
                $html .= '<div class="item">• ' . $certText . '</div>';
            }
            
            $html .= '</div>';
        }

        // 7. Analytical Skills (Only if strong, otherwise merge with Technical Skills)
        if ($user->analyticalSkills->count() > 0 && $user->major === 'IT') {
            $html .= '<div class="section">';
            $html .= '<h2>ANALYTICAL SKILLS</h2>';
            
            $skillNames = $user->analyticalSkills->pluck('skill_name')->toArray();
            $html .= '<div class="skills-inline">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
            
            $html .= '</div>';
        }

        // 8. Soft Skills (Short, no filler)
        if ($user->softSkills->count() >= 3) {
            $html .= '<div class="section">';
            $html .= '<h2>SOFT SKILLS</h2>';
            
            $skillNames = $user->softSkills->pluck('soft_name')->toArray();
            $html .= '<div class="skills-inline">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
            
            $html .= '</div>';
        }

        // 9. Languages
        if ($user->languages->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>LANGUAGES</h2>';
            
            foreach ($user->languages as $lang) {
                $html .= '<div class="item">';
                $html .= '<strong>' . htmlspecialchars($lang->language_name) . '</strong> - ' . 
                        htmlspecialchars($lang->proficiency_level);
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // 10. Activities & Memberships (Last, optional)
        if ($user->activities->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>ACTIVITIES & VOLUNTEER WORK</h2>';
            
            foreach ($user->activities as $act) {
                $html .= '<div class="item">';
                $html .= '<div class="job-title">' . htmlspecialchars($act->activity_title) . '</div>';
                
                if ($act->organization) {
                    $html .= '<div class="company">' . htmlspecialchars($act->organization) . '</div>';
                }
                
                if ($act->activity_date) {
                    $html .= '<div class="date">' . date('M Y', strtotime($act->activity_date)) . '</div>';
                }
                
                if ($act->description_activity) {
                    $bullets = $this->formatBulletPoints($act->description_activity);
                    foreach ($bullets as $bullet) {
                        $html .= '<div class="bullet">• ' . htmlspecialchars($bullet) . '</div>';
                    }
                }
                
                if ($act->activity_link) {
                    $link = $act->activity_link;
                    if (!preg_match('/^https?:\/\//', $link)) {
                        $link = 'https://' . $link;
                    }
                    $linkText = str_replace(['http://', 'https://'], '', $act->activity_link);
                    $html .= '<div style="font-size: 10pt;"><a href="' . htmlspecialchars($link) . '" style="color: #666666; text-decoration: underline;">' . htmlspecialchars($linkText) . '</a></div>';
                }
                
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // Professional Memberships (if applicable)
        if ($user->memberships->count() > 0) {
            $html .= '<div class="section">';
            $html .= '<h2>PROFESSIONAL MEMBERSHIPS</h2>';
            
            foreach ($user->memberships as $m) {
                $html .= '<div class="item">';
                $html .= '<strong>' . htmlspecialchars($m->organization_name) . '</strong>';
                if ($m->membership_type) {
                    $html .= ' - ' . htmlspecialchars($m->membership_type);
                }
                if ($m->start_date_membership || $m->end_date_membership) {
                    $startDate = $m->start_date_membership ? date('M Y', strtotime($m->start_date_membership)) : '';
                    $endDate = $m->end_date_membership ? date('M Y', strtotime($m->end_date_membership)) : 'Present';
                    $html .= ' (' . $startDate . ' - ' . $endDate . ')';
                }
                if ($m->membership_status) {
                    $html .= ' | ' . htmlspecialchars($m->membership_status);
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        // Research (Medical major only)
        if ($user->research->count() > 0 && $user->major === 'Medicine') {
            $html .= '<div class="section">';
            $html .= '<h2>RESEARCH & PUBLICATIONS</h2>';
            
            foreach ($user->research as $research) {
                $html .= '<div class="item">';
                $html .= '<div class="job-title">' . htmlspecialchars($research->title) . '</div>';
                if ($research->publication_year) {
                    $html .= '<div class="date">' . htmlspecialchars($research->publication_year) . '</div>';
                }
                if ($research->description) {
                    $html .= '<p>' . nl2br(htmlspecialchars($research->description)) . '</p>';
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Format bullet points for ATS (STAR method enhancement)
     */
    private function formatBulletPoints($text)
    {
        $lines = explode("\n", $text);
        $bullets = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Remove existing bullets
            $line = preg_replace('/^[-•*]\s*/', '', $line);
            
            // Enhance with action verbs if needed
            $line = $this->enhanceBulletPoint($line);
            
            $bullets[] = $line;
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

        // Header
        $section->addText(
            strtoupper(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8')),
            ['size' => 18, 'bold' => true],
            ['alignment' => Jc::CENTER]
        );

        if ($user->job_title) {
            $section->addText(
                htmlspecialchars($user->job_title, ENT_QUOTES, 'UTF-8'),
                ['size' => 11],
                ['alignment' => Jc::CENTER]
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

        if (!empty($contact)) {
            $section->addText(
                implode(' | ', $contact),
                ['size' => 10],
                ['alignment' => Jc::CENTER]
            );
        }

        $section->addTextBreak(1);

        // 1. Professional Summary
        if ($summary) {
            $section->addText('PROFESSIONAL SUMMARY', ['bold' => true, 'size' => 12]);
            $section->addText(htmlspecialchars($summary, ENT_QUOTES, 'UTF-8'));
            $section->addTextBreak(1);
        }

        // 2. Technical Skills (ATS filters from here - MUST be before Experience)
        // Technical Skills
        if ($user->skills->count() > 0) {
            $section->addText('TECHNICAL SKILLS', ['bold' => true, 'size' => 12]);
            
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName) {
                    $section->addText(htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                }
                $skillNames = $skills->pluck('skill_name')->toArray();
                $section->addText(implode(', ', array_map(function($s) {
                    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
                }, $skillNames)));
            }
            
            $section->addTextBreak(1);
        }

        // 3. Work Experience (Most important section for HR)
        if ($user->experiences->count() > 0) {
            $section->addText('PROFESSIONAL EXPERIENCE', ['bold' => true, 'size' => 12]);
            
            $experiences = $user->experiences->sortByDesc(function($exp) {
                return $exp->start_date ? strtotime($exp->start_date) : 0;
            });
            
            foreach ($experiences as $exp) {
                $section->addText(htmlspecialchars($exp->title, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                
                $companyLine = [];
                if ($exp->company) $companyLine[] = htmlspecialchars($exp->company);
                if ($exp->location) $companyLine[] = htmlspecialchars($exp->location);
                
                if (!empty($companyLine)) {
                    $section->addText(implode(' - ', $companyLine), ['bold' => true]);
                }
                
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $section->addText($startDate . ' - ' . $endDate, ['italic' => true, 'size' => 10]);
                
                if ($exp->description) {
                    $bullets = $this->formatBulletPoints($exp->description);
                    foreach ($bullets as $bullet) {
                        $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0);
                    }
                }
                
                $section->addTextBreak(1);
            }
            
            $section->addTextBreak(1);
        }

        // 4. Projects (Especially important for Tech/Fresh/Juniors)
        if ($user->projects->count() > 0) {
            $section->addText('PROJECTS', ['bold' => true, 'size' => 12]);
            
            foreach ($user->projects as $proj) {
                $section->addText(htmlspecialchars($proj->project_title, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                
                if ($proj->description) {
                    $bullets = $this->formatBulletPoints($proj->description);
                    foreach ($bullets as $bullet) {
                        $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0);
                    }
                }
                
                if ($proj->technologies_used) {
                    $section->addText('Technologies: ' . htmlspecialchars($proj->technologies_used, ENT_QUOTES, 'UTF-8'), ['size' => 10]);
                }
                
                $section->addTextBreak(1);
            }
            
            $section->addTextBreak(1);
        }

        // 5. Education
        if ($education->count() > 0) {
            $section->addText('EDUCATION', ['bold' => true, 'size' => 12]);
            
            foreach ($education as $edu) {
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $field = $edu->field_of_study ?? '';
                $university = $edu->university_name ?? $edu->university ?? '';
                
                if ($degree) {
                    $degreeText = htmlspecialchars($degree, ENT_QUOTES, 'UTF-8');
                    if ($field) {
                        $degreeText .= ' in ' . htmlspecialchars($field, ENT_QUOTES, 'UTF-8');
                    }
                    $section->addText($degreeText, ['bold' => true]);
                }
                
                if ($university) {
                    $section->addText(htmlspecialchars($university, ENT_QUOTES, 'UTF-8'));
                }
                
                $startYear = $edu->start_year ?? '';
                $endYear = ($edu->end_year ?? 0) == 0 ? 'Present' : ($edu->end_year ?? '');
                if ($startYear || $endYear) {
                    $section->addText($startYear . ' - ' . $endYear, ['italic' => true, 'size' => 10]);
                }
                
                $section->addTextBreak(1);
            }
        }

        // 6. Certifications (Supporting, not essential)
        if ($user->certifications->count() > 0) {
            $section->addText('CERTIFICATIONS', ['bold' => true, 'size' => 12]);
            
            foreach ($user->certifications as $cert) {
                $certText = htmlspecialchars($cert->certifications_name, ENT_QUOTES, 'UTF-8');
                if ($cert->issuing_org) {
                    $certText .= ' - ' . htmlspecialchars($cert->issuing_org, ENT_QUOTES, 'UTF-8');
                }
                
                $issueDate = $cert->issue_date ? date('M Y', strtotime($cert->issue_date)) : '';
                if ($issueDate) {
                    $certText .= ', ' . $issueDate;
                }
                
                $section->addListItem($certText, 0);
            }
            
            $section->addTextBreak(1);
        }

        // 7. Analytical Skills (Only if strong)
        if ($user->analyticalSkills->count() > 0 && $user->major === 'IT') {
            $section->addText('ANALYTICAL SKILLS', ['bold' => true, 'size' => 12]);
            
            $skillNames = $user->analyticalSkills->pluck('skill_name')->toArray();
            $section->addText(implode(', ', array_map(function($s) {
                return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
            }, $skillNames)));
            
            $section->addTextBreak(1);
        }

        // 8. Soft Skills (Short, no filler)
        if ($user->softSkills->count() >= 3) {
            $section->addText('SOFT SKILLS', ['bold' => true, 'size' => 12]);
            
            $skillNames = $user->softSkills->pluck('soft_name')->toArray();
            $section->addText(implode(', ', array_map(function($s) {
                return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
            }, $skillNames)));
            
            $section->addTextBreak(1);
        }

        // 9. Languages
        if ($user->languages->count() > 0) {
            $section->addText('LANGUAGES', ['bold' => true, 'size' => 12]);
            
            foreach ($user->languages as $lang) {
                $section->addListItem(
                    htmlspecialchars($lang->language_name, ENT_QUOTES, 'UTF-8') . ' - ' . 
                    htmlspecialchars($lang->proficiency_level, ENT_QUOTES, 'UTF-8'),
                    0
                );
            }
            
            $section->addTextBreak(1);
        }

        // 10. Activities & Volunteer Work (Last, optional)
        if ($user->activities->count() > 0) {
            $section->addText('ACTIVITIES & VOLUNTEER WORK', ['bold' => true, 'size' => 12]);
            
            foreach ($user->activities as $act) {
                $section->addText(htmlspecialchars($act->activity_title, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                
                if ($act->organization) {
                    $section->addText(htmlspecialchars($act->organization, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                }
                
                if ($act->activity_date) {
                    $section->addText(date('M Y', strtotime($act->activity_date)), ['italic' => true, 'size' => 10]);
                }
                
                if ($act->description_activity) {
                    $bullets = $this->formatBulletPoints($act->description_activity);
                    foreach ($bullets as $bullet) {
                        $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0);
                    }
                }
                
                $section->addTextBreak(1);
            }
        }

        // Professional Memberships (if applicable)
        if ($user->memberships->count() > 0) {
            $section->addText('PROFESSIONAL MEMBERSHIPS', ['bold' => true, 'size' => 12]);
            
            foreach ($user->memberships as $m) {
                $membershipText = htmlspecialchars($m->organization_name, ENT_QUOTES, 'UTF-8');
                if ($m->membership_type) {
                    $membershipText .= ' - ' . htmlspecialchars($m->membership_type, ENT_QUOTES, 'UTF-8');
                }
                if ($m->start_date_membership || $m->end_date_membership) {
                    $startDate = $m->start_date_membership ? date('M Y', strtotime($m->start_date_membership)) : '';
                    $endDate = $m->end_date_membership ? date('M Y', strtotime($m->end_date_membership)) : 'Present';
                    $membershipText .= ' (' . $startDate . ' - ' . $endDate . ')';
                }
                $section->addListItem($membershipText, 0);
            }
        }
        if ($user->activities->count() > 0) {
            $section->addText('ACTIVITIES & VOLUNTEER WORK', ['bold' => true, 'size' => 12]);
            
            foreach ($user->activities as $act) {
                $section->addText(htmlspecialchars($act->activity_title, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                
                if ($act->organization) {
                    $section->addText(htmlspecialchars($act->organization, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                }
                
                if ($act->activity_date) {
                    $section->addText(date('M Y', strtotime($act->activity_date)), ['italic' => true, 'size' => 10]);
                }
                
                if ($act->description_activity) {
                    $bullets = $this->formatBulletPoints($act->description_activity);
                    foreach ($bullets as $bullet) {
                        $section->addListItem(htmlspecialchars($bullet, ENT_QUOTES, 'UTF-8'), 0);
                    }
                }
                
                if ($act->activity_link) {
                    $linkText = str_replace(['http://', 'https://'], '', $act->activity_link);
                    $section->addText(htmlspecialchars($linkText, ENT_QUOTES, 'UTF-8'), ['size' => 10]);
                }
                
                $section->addTextBreak(1);
            }
        }

        // Save and output
        try {
            $tempFile = sys_get_temp_dir() . '/CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '_' . time() . '.docx';
            IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

            if (!file_exists($tempFile)) {
                abort(500, 'Error creating Word file');
            }

            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.docx"');
            header('Content-Length: ' . filesize($tempFile));
            readfile($tempFile);

            @unlink($tempFile);
            exit;
        } catch (\Exception $e) {
            abort(500, 'Error generating Word file: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download wishes book (Word document)
     */
    public function generateWishes($qr_id)
    {
        try {
            $user = User::with('wishes')->find($qr_id);

            if (!$user) {
                abort(404, 'User not found');
            }
        } catch (\Exception $e) {
            abort(500, 'Error loading user data: ' . $e->getMessage());
        }

        $wishes = $user->wishes()->orderBy('created_at', 'DESC')->get();

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('ar-SA'));

        $phpWord->addFontStyle('coverTitleStyle', ['bold' => true, 'size' => 36, 'color' => 'FFFFFF', 'name' => 'Arial']);
        $phpWord->addFontStyle('coverSubtitleStyle', ['italic' => true, 'size' => 24, 'color' => 'FFD700', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishSenderStyle', ['bold' => true, 'size' => 18, 'color' => '4169E1', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishTextStyle', ['size' => 16, 'color' => '333333', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishDateStyle', ['size' => 12, 'color' => 'A9A9A9', 'name' => 'Arial']);
        $phpWord->addFontStyle('signatureLabelStyle', ['italic' => true, 'size' => 12, 'color' => '87CEEB', 'name' => 'Arial']);

        $cover = $phpWord->addSection([
            'pageSizeW' => 12000,
            'pageSizeH' => 15840,
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1200,
            'marginRight' => 1200,
            'differentFirstPageHeaderFooter' => true,
        ]);

        $cover->addText('🎓 تهانينا بالتخرج 🎓', 'coverTitleStyle', ['alignment' => Jc::CENTER]);
        $cover->addTextBreak(2);
        $cover->addText('دفتر الأمنيات والتهاني للخريج', 'coverSubtitleStyle', ['alignment' => Jc::CENTER]);
        $cover->addTextBreak(10);
        $cover->addText(date('Y'), ['bold' => true, 'size' => 16, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);

        $cover->addPageBreak();

        $section = $phpWord->addSection();

        foreach ($wishes as $wish) {
            $section->addText('👤 ' . ($wish->sender_name ?? 'Unknown'), 'wishSenderStyle', ['alignment' => Jc::START]);
            $section->addText(' ' . $wish->created_at->format('Y-m-d'), 'wishDateStyle', ['alignment' => Jc::END]);
            $section->addTextBreak(1);

            if ($wish->image) {
                $imagePath = storage_path('app/public/' . $wish->image);
                if (file_exists($imagePath)) {
                    $section->addImage($imagePath, [
                        'width' => 400,
                        'height' => 400,
                        'alignment' => Jc::CENTER
                    ]);
                }
            }

            $section->addTextBreak(2);
            $section->addText($wish->witsh_text ?? '', 'wishTextStyle', ['alignment' => Jc::CENTER]);
            $section->addTextBreak(2);

            if ($wish->singnature) {
                $signaturePath = storage_path('app/public/' . $wish->singnature);
                if (file_exists($signaturePath)) {
                    $section->addText('✨ التوقيع', 'signatureLabelStyle', ['alignment' => Jc::RIGHT]);
                    $section->addImage($signaturePath, [
                        'width' => 120,
                        'height' => 60,
                        'alignment' => Jc::RIGHT
                    ]);
                }
            } else {
                $section->addText('✨ التوقيع: (غير متوفر)', 'signatureLabelStyle', ['alignment' => Jc::RIGHT]);
            }

            $section->addPageBreak();
        }

        try {
            $filename = "دفتر_التهاني_والأمنيات_{$qr_id}.docx";
            header("Content-Description: File Transfer");
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save("php://output");
            exit;
        } catch (\Exception $e) {
            abort(500, 'Error generating Wishes file: ' . $e->getMessage());
        }
    }
}
