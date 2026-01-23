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
     * Generate and download PDF CV
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

        // Fetch education data (if education table exists)
        try {
            $education = DB::table('education')
                ->where('qr_id', $qr_id)
                ->get();
        } catch (\Exception $e) {
            $education = collect([]);
        }

        // Prepare HTML content with ATS-friendly styling (similar to Ahmed Al Darabee CV)
        $html = '<style>
            body { font-family: "Arial", "Helvetica", sans-serif; font-size: 10pt; line-height: 1.5; color: #000; margin: 0; padding: 0; }
            h1 { font-size: 18pt; font-weight: bold; color: #000; text-align: center; margin: 0 0 5px 0; letter-spacing: 0.5px; }
            h2 { font-size: 12pt; font-weight: bold; color: #000; margin-top: 15px; margin-bottom: 8px; text-transform: uppercase; border-bottom: 1px solid #000; padding-bottom: 2px; }
            h3 { font-size: 11pt; font-weight: bold; color: #000; margin-top: 8px; margin-bottom: 3px; }
            h4 { font-size: 10pt; font-weight: normal; color: #000; text-align: center; margin: 0 0 8px 0; }
            .header { text-align: center; margin-bottom: 15px; }
            .contact-line { text-align: center; font-size: 9pt; margin: 5px 0; line-height: 1.6; }
            .contact-line span { margin: 0 5px; }
            .section { margin-top: 12px; margin-bottom: 12px; }
            .section-content { margin-left: 0; }
            ul { padding-left: 20px; margin: 5px 0; }
            li { margin: 2px 0; line-height: 1.4; }
            .section p { margin: 4px 0; line-height: 1.4; }
            .experience-item, .project-item, .education-item, .cert-item { margin-bottom: 10px; }
            .job-title { font-weight: bold; font-size: 10pt; }
            .company-name { font-weight: bold; }
            .date-range { font-size: 9pt; color: #333; margin: 2px 0; }
            .skills-list { margin: 3px 0; line-height: 1.4; }
            .skill-category { font-weight: bold; margin-top: 6px; margin-bottom: 2px; }
            .bullet-point { margin: 2px 0; padding-left: 5px; }
            .project-link { color: #000; text-decoration: none; font-size: 9pt; }
            a { color: #000; text-decoration: none; }
            a:visited { color: #000; }
            .divider { border-top: 1px solid #ccc; margin: 8px 0; }
            @media print {
                body { font-size: 9pt; }
                .section { page-break-inside: avoid; }
            }
        </style>';

        // Header - Name and Job Title (similar to Ahmed CV)
        $html .= '<div class="header">';
        $html .= '<h1>' . htmlspecialchars($user->name) . '</h1>';
        if ($user->job_title) {
            $html .= '<h4>' . htmlspecialchars($user->job_title) . '</h4>';
        }
        $html .= '</div>';

        // Contact Information (single line format like Ahmed CV)
        $contactParts = [];
        if ($user->city) {
            $contactParts[] = htmlspecialchars($user->city);
        }
        if ($user->phone) {
            $contactParts[] = htmlspecialchars($user->phone);
        }
        if ($user->email) {
            $contactParts[] = htmlspecialchars($user->email);
        }
        if ($user->profile_website) {
            $linkText = str_replace(['http://', 'https://'], '', $user->profile_website);
            $contactParts[] = htmlspecialchars($linkText);
        }
        
        if (!empty($contactParts)) {
            $html .= '<div class="contact-line">' . implode(' / ', $contactParts) . '</div>';
        }
        
        // Social Links (separate line)
        $socialParts = [];
        if ($user->linkedin_profile) {
            $linkText = str_replace(['http://', 'https://', 'www.linkedin.com/in/'], '', $user->linkedin_profile);
            $socialParts[] = 'LinkedIn: ' . htmlspecialchars($linkText);
        }
        if ($user->github_profile) {
            $linkText = str_replace(['http://', 'https://', 'www.github.com/'], '', $user->github_profile);
            $socialParts[] = 'GitHub: ' . htmlspecialchars($linkText);
        }
        
        if (!empty($socialParts)) {
            $html .= '<div class="contact-line">' . implode(' / ', $socialParts);
            $html .= ' / Last CV Updates: ' . date('M Y') . '</div>';
        } else {
            $html .= '<div class="contact-line">Last CV Updates: ' . date('M Y') . '</div>';
        }

        // Professional Summary
        if ($user->profile_summary) {
            $html .= '<div class="section"><h2>Summary</h2>';
            $html .= '<div class="section-content"><p>' . nl2br(htmlspecialchars($user->profile_summary)) . '</p></div></div>';
        }

        // Work Experience (formatted like Ahmed CV)
        if ($user->experiences->count() > 0) {
            $html .= '<div class="section"><h2>Work Experience</h2>';
            foreach ($user->experiences as $exp) {
                $html .= '<div class="experience-item">';
                
                // Company name and job title on same line
                $companyLine = '';
                if ($exp->company) {
                    $companyLine = '<span class="company-name">' . htmlspecialchars($exp->company) . '</span>';
                }
                if ($exp->location) {
                    $companyLine .= ($companyLine ? ': ' : '') . htmlspecialchars($exp->location);
                }
                if ($companyLine) {
                    $html .= '<div class="job-title">' . htmlspecialchars($exp->title) . '</div>';
                    $html .= '<div>' . $companyLine . '</div>';
                } else {
                    $html .= '<div class="job-title">' . htmlspecialchars($exp->title) . '</div>';
                }
                
                // Date range
                $startDate = $exp->start_date ? date('M Y', strtotime($exp->start_date)) : '';
                $endDate = $exp->end_date ? date('M Y', strtotime($exp->end_date)) : 'Present';
                $html .= '<div class="date-range">' . $startDate . ' – ' . $endDate;
                if ($exp->is_internship) {
                    $html .= ' (Internship)';
                }
                $html .= '</div>';
                
                // Description as bullet points
                if ($exp->description) {
                    $descriptionLines = explode("\n", $exp->description);
                    foreach ($descriptionLines as $line) {
                        $line = trim($line);
                        if ($line) {
                            // Remove existing bullets if any
                            $line = preg_replace('/^[-•*]\s*/', '', $line);
                            $html .= '<div class="bullet-point">- ' . htmlspecialchars($line) . '</div>';
                        }
                    }
                }
                
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Education (formatted like Ahmed CV)
        if ($education->count() > 0) {
            $html .= '<div class="section"><h2>Education and Qualifications</h2>';
            foreach ($education as $edu) {
                $html .= '<div class="education-item">';
                $degree = $edu->degree_name ?? $edu->degree ?? '';
                $field = $edu->field_of_study ?? '';
                $university = $edu->university_name ?? $edu->university ?? '';
                $startYear = $edu->start_year ?? '';
                $endYear = ($edu->end_year ?? 0) == 0 ? 'Present' : ($edu->end_year ?? '');
                
                if ($degree) {
                    $degreeText = htmlspecialchars($degree);
                    if ($field) {
                        $degreeText .= ' in ' . htmlspecialchars($field);
                    }
                    $html .= '<div class="job-title">' . $degreeText . '</div>';
                }
                if ($university) {
                    $html .= '<div>' . htmlspecialchars($university);
                    if ($startYear || $endYear) {
                        $html .= ' - ' . htmlspecialchars($university);
                    }
                    $html .= '</div>';
                }
                if ($startYear || $endYear) {
                    $html .= '<div class="date-range">' . $startYear . ' – ' . $endYear . '</div>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Projects (formatted like Ahmed CV with [Source Code])
        if ($user->projects->count() > 0) {
            $html .= '<div class="section"><h2>Projects</h2>';
            foreach ($user->projects as $proj) {
                $html .= '<div class="project-item">';
                
                // Project title with [Source Code] link
                $projectTitle = htmlspecialchars($proj->project_title);
                if ($proj->link) {
                    $linkText = str_replace(['http://', 'https://'], '', $proj->link);
                    $projectTitle = '[Source Code] ' . $projectTitle;
                }
                $html .= '<div class="job-title">' . $projectTitle . '</div>';
                
                // Description as bullet points
                if ($proj->description) {
                    $descriptionLines = explode("\n", $proj->description);
                    foreach ($descriptionLines as $line) {
                        $line = trim($line);
                        if ($line) {
                            $line = preg_replace('/^[-•*]\s*/', '', $line);
                            $html .= '<div class="bullet-point">- ' . htmlspecialchars($line) . '</div>';
                        }
                    }
                }
                
                // Technologies used
                if ($proj->technologies_used) {
                    $html .= '<div class="bullet-point" style="margin-top: 5px;"><strong>Technologies:</strong> ' . htmlspecialchars($proj->technologies_used) . '</div>';
                }
                
                // Link
                if ($proj->link) {
                    $linkText = str_replace(['http://', 'https://'], '', $proj->link);
                    $html .= '<div class="project-link">' . htmlspecialchars($linkText) . '</div>';
                }
                
                $html .= '<div class="divider"></div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Technical Skills (formatted like Ahmed CV)
        if ($user->skills->count() > 0) {
            $html .= '<div class="section"><h2>Technical Skills</h2>';
            
            // Group skills by category
            $skillsByCategory = $user->skills->groupBy('category.category_name');
            $skillLines = [];
            
            foreach ($skillsByCategory as $categoryName => $skills) {
                if ($categoryName) {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = '<strong>' . htmlspecialchars($categoryName) . ':</strong> ' . implode(', ', array_map('htmlspecialchars', $skillNames));
                } else {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $skillLines[] = implode(', ', array_map('htmlspecialchars', $skillNames));
                }
            }
            
            $html .= '<div class="skills-list">' . implode('<br/>', $skillLines) . '</div>';
            $html .= '</div>';
        }

        // Medical Skills (if applicable)
        if ($user->medicalSkills->count() > 0) {
            $html .= '<div class="section"><h2>MEDICAL SKILLS</h2>';
            foreach ($user->medicalSkills->groupBy('category.category_name') as $categoryName => $skills) {
                if ($categoryName) {
                    $html .= '<div class="skill-category">' . htmlspecialchars($categoryName) . ':</div>';
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div class="skills-list">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
                } else {
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= '<div class="skills-list">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
                }
            }
            $html .= '</div>';
        }

        // Soft Skills (formatted like Ahmed CV)
        if ($user->softSkills->count() > 0) {
            $html .= '<div class="section"><h2>Soft and Behavioral Skills</h2>';
            foreach ($user->softSkills as $soft) {
                $html .= '<div class="bullet-point">- ' . htmlspecialchars($soft->soft_name) . '</div>';
            }
            $html .= '</div>';
        }

        // Analytical Skills
        if ($user->analyticalSkills->count() > 0) {
            $html .= '<div class="section"><h2>ANALYTICAL SKILLS</h2>';
            $skillNames = $user->analyticalSkills->pluck('skill_name')->toArray();
            $html .= '<div class="skills-list">' . implode(', ', array_map('htmlspecialchars', $skillNames)) . '</div>';
            $html .= '</div>';
        }

        // Core Competencies
        if ($user->coreCompetencies->count() > 0) {
            $html .= '<div class="section"><h2>CORE COMPETENCIES</h2><ul>';
            foreach ($user->coreCompetencies as $comp) {
                $html .= '<li><strong>' . htmlspecialchars($comp->competency_name) . '</strong>';
                if ($comp->description) {
                    $html .= ': ' . htmlspecialchars($comp->description);
                }
                $html .= '</li>';
            }
            $html .= '</ul></div>';
        }

        // Certifications (formatted like Ahmed CV)
        if ($user->certifications->count() > 0) {
            $html .= '<div class="section"><h2>Certifications and Courses</h2>';
            $html .= '<h3>See My Achievements</h3>';
            foreach ($user->certifications as $c) {
                $html .= '<div class="cert-item">';
                $html .= '<div class="bullet-point">- ';
                
                $certText = htmlspecialchars($c->certifications_name);
                if ($c->issuing_org) {
                    $certText .= ' at ' . htmlspecialchars($c->issuing_org);
                }
                
                $issueDate = $c->issue_date ? date('M Y', strtotime($c->issue_date)) : '';
                $expiryDate = $c->expiration_date ? date('M Y', strtotime($c->expiration_date)) : '';
                
                if ($issueDate) {
                    $certText .= ' ' . $issueDate;
                    if ($expiryDate && $expiryDate != $issueDate) {
                        $certText .= ' – ' . $expiryDate;
                    } else if (!$expiryDate) {
                        $certText .= ' – Progress';
                    }
                }
                
                $html .= $certText . '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Languages
        if ($user->languages->count() > 0) {
            $html .= '<div class="section"><h2>LANGUAGES</h2><ul>';
            foreach ($user->languages as $l) {
                $html .= '<li><strong>' . htmlspecialchars($l->language_name) . '</strong> – ' . htmlspecialchars($l->proficiency_level) . '</li>';
            }
            $html .= '</ul></div>';
        }

        // Professional Memberships
        if ($user->memberships->count() > 0) {
            $html .= '<div class="section"><h2>PROFESSIONAL MEMBERSHIPS</h2><ul>';
            foreach ($user->memberships as $m) {
                $html .= '<li><strong>' . htmlspecialchars($m->organization_name) . '</strong>';
                if ($m->membership_type) {
                    $html .= ' – ' . htmlspecialchars($m->membership_type);
                }
                if ($m->start_date_membership || $m->end_date_membership) {
                    $startDate = $m->start_date_membership ? date('M Y', strtotime($m->start_date_membership)) : '';
                    $endDate = $m->end_date_membership ? date('M Y', strtotime($m->end_date_membership)) : 'Present';
                    $html .= ' (' . $startDate . ' - ' . $endDate . ')';
                }
                if ($m->membership_status) {
                    $html .= ' | ' . htmlspecialchars($m->membership_status);
                }
                $html .= '</li>';
            }
            $html .= '</ul></div>';
        }

        // Activities
        if ($user->activities->count() > 0) {
            $html .= '<div class="section"><h2>ACTIVITIES & VOLUNTEER WORK</h2>';
            foreach ($user->activities as $act) {
                $html .= '<div class="experience-item">';
                $html .= '<h3>' . htmlspecialchars($act->activity_title) . '</h3>';
                if ($act->organization) {
                    $html .= '<p><strong>' . htmlspecialchars($act->organization) . '</strong></p>';
                }
                if ($act->activity_date) {
                    $html .= '<p class="date-range">' . date('M Y', strtotime($act->activity_date)) . '</p>';
                }
                if ($act->description_activity) {
                    $html .= '<p>' . nl2br(htmlspecialchars($act->description_activity)) . '</p>';
                }
                if ($act->activity_link) {
                    $linkText = str_replace(['http://', 'https://'], '', $act->activity_link);
                    $html .= '<p>🔗 ' . htmlspecialchars($linkText) . '</p>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Research (for Medical major)
        if ($user->research->count() > 0) {
            $html .= '<div class="section"><h2>RESEARCH & PUBLICATIONS</h2>';
            foreach ($user->research as $r) {
                $html .= '<div class="experience-item">';
                $html .= '<h3>' . htmlspecialchars($r->title) . '</h3>';
                if ($r->publication_year) {
                    $html .= '<p class="date-range">' . htmlspecialchars($r->publication_year) . '</p>';
                }
                if ($r->description) {
                    $html .= '<p>' . nl2br(htmlspecialchars($r->description)) . '</p>';
                }
                if ($r->link) {
                    $linkText = str_replace(['http://', 'https://'], '', $r->link);
                    $html .= '<p>🔗 ' . htmlspecialchars($linkText) . '</p>';
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        // Interests
        if ($user->interests->count() > 0) {
            $html .= '<div class="section"><h2>INTERESTS</h2>';
            $interestNames = $user->interests->pluck('interest_name')->toArray();
            $html .= '<div class="skills-list">' . implode(', ', array_map('htmlspecialchars', $interestNames)) . '</div>';
            $html .= '</div>';
        }

        // Generate PDF and output
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P'
            ]);
            $mpdf->WriteHTML($html);
            $mpdf->Output('CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.pdf', 'D');
            exit;
        } catch (\Exception $e) {
            abort(500, 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Generate and download Word CV
     */
    public function generateWord($qr_id)
    {
        try {
            $user = User::with([
                'projects',
                'skills.category',
                'softSkills',
                'certifications',
                'languages'
            ])->find($qr_id);

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

        // Build the Word document
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        $phpWord->addFontStyle('headerTitle', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
        $phpWord->addFontStyle('contactLine', ['size' => 9, 'color' => '000000'], ['alignment' => Jc::CENTER]);
        $phpWord->addFontStyle('sectionTitle', ['bold' => true, 'size' => 12, 'color' => '000000']);

        $listBullet = ['listType' => ListItem::TYPE_BULLET_FILLED, 'indent' => 576];

        $section = $phpWord->addSection([
            'marginTop' => 600,
            'marginBottom' => 600,
            'marginLeft' => 800,
            'marginRight' => 800
        ]);

        // Header - Name & Job Title
        $section->addText(
            strtoupper(htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8')),
            ['size' => 36, 'bold' => false],
            ['alignment' => Jc::CENTER]
        );

        $section->addText(
            $user->job_title ?? 'Professional',
            ['size' => 20],
            ['alignment' => Jc::CENTER]
        );
        $section->addText(
            str_repeat('━', 105),
            ['bold' => true, 'color' => '000000', 'size' => 8],
            ['alignment' => Jc::CENTER]
        );

        // Contact Links
        $textRun = $section->addTextRun(['alignment' => Jc::CENTER]);

        if ($user->city) {
            $textRun->addText('📍 ' . $user->city . '     ', ['color' => '000000']);
        }

        if ($user->phone) {
            $textRun->addText('📞 ' . $user->phone . '     ', ['color' => '000000']);
        }

        if ($user->linkedin_profile) {
            $textRun->addLink($user->linkedin_profile, 'LinkedIn', ['color' => '000000', 'underline' => 'none']);
            $textRun->addText('     ');
        }

        if ($user->github_profile) {
            $textRun->addLink($user->github_profile, 'GitHub', ['color' => '000000', 'underline' => 'none']);
            $textRun->addText('     ');
        }

        if ($user->email) {
            $section->addTextBreak(1);
            $textRun2 = $section->addTextRun(['alignment' => Jc::CENTER]);
            $textRun2->addLink('mailto:' . $user->email, $user->email, ['color' => '000000', 'underline' => 'none']);
        }

        if ($user->profile_website) {
            if (!isset($textRun2)) {
                $section->addTextBreak(1);
                $textRun2 = $section->addTextRun(['alignment' => Jc::CENTER]);
            }
            $textRun2->addText('     ');
            $textRun2->addLink($user->profile_website, 'Profile', ['color' => '000000', 'underline' => 'none']);
        }

        $section->addTextBreak(1.5);

        // Professional Summary
        if ($user->profile_summary) {
            $section->addText('PROFESSIONAL SUMMARY', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'color' => '000000', 'size' => 8]);
            foreach (explode("\n", $user->profile_summary) as $line) {
                $section->addText(htmlspecialchars(trim($line), ENT_QUOTES, 'UTF-8'));
            }
            $section->addTextBreak(2);
        }

        // Education
        if ($education->count() > 0) {
            $section->addText('EDUCATION', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'color' => '000000', 'size' => 8]);
            $section->addTextBreak(1);
            foreach ($education as $edu) {
                $section->addText(htmlspecialchars($edu->degree, ENT_QUOTES, 'UTF-8'), ['size' => 12]);
                $section->addText(htmlspecialchars($edu->university, ENT_QUOTES, 'UTF-8'), ['size' => 11]);
                $endYear = $edu->end_year == 0 ? 'Present' : $edu->end_year;
                $section->addText($edu->start_year . ' - ' . $endYear, ['size' => 11], ['alignment' => Jc::RIGHT]);
                $section->addTextBreak(1);
            }
            $section->addTextBreak(1);
        }

        // Projects
        if ($user->projects->count() > 0) {
            $section->addText('PROJECTS', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);
            $section->addTextBreak(1);
            foreach ($user->projects as $proj) {
                $section->addText(htmlspecialchars($proj->project_title, ENT_QUOTES, 'UTF-8'), ['size' => 12]);
                foreach (explode("\n", $proj->description ?? '') as $desc) {
                    if (trim($desc)) {
                        $section->addText('  ' . htmlspecialchars(trim($desc), ENT_QUOTES, 'UTF-8'), ['size' => 11]);
                    }
                }
                if ($proj->technologies_used) {
                    $section->addText('Technologies Used: ' . htmlspecialchars($proj->technologies_used, ENT_QUOTES, 'UTF-8'), ['size' => 10]);
                }
                if ($proj->link) {
                    $section->addLink("https://{$proj->link}", $proj->link, ['underline' => 'none']);
                }
                $section->addTextBreak(1);
            }
        }

        // Technical Skills
        if ($user->skills->count() > 0) {
            $section->addText('TECHNICAL SKILLS', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);

            foreach ($user->skills->groupBy('category.category_name') as $categoryName => $skills) {
                if ($categoryName) {
                    $section->addText("🗂️ " . htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8'), ['bold' => true]);
                    foreach ($skills as $skill) {
                        $section->addListItem(htmlspecialchars($skill->skill_name, ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
                    }
                    $section->addTextBreak(1);
                } else {
                    // If no category, just list skills
                    foreach ($skills as $skill) {
                        $section->addListItem(htmlspecialchars($skill->skill_name, ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
                    }
                    $section->addTextBreak(1);
                }
            }
        }

        // Soft Skills
        $section->addText('SOFT SKILLS', 'sectionTitle');
        $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);
        foreach ($user->softSkills as $skill) {
            $section->addListItem(htmlspecialchars($skill->soft_name, ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
        }
        $section->addTextBreak(1);

        // Certifications
        if ($user->certifications->count() > 0) {
            $section->addText('CERTIFICATIONS', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);
            foreach ($user->certifications as $c) {
                $issueDate = $c->issue_date ? date('M Y', strtotime($c->issue_date)) : '';
                $section->addListItem(htmlspecialchars("{$c->certifications_name} – {$c->issuing_org}, {$issueDate}", ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
            }
            $section->addTextBreak(1);
        }

        // Languages
        if ($user->languages->count() > 0) {
            $section->addText('LANGUAGES', 'sectionTitle');
            $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);
            foreach ($user->languages as $l) {
                $section->addListItem(htmlspecialchars("{$l->language_name} – {$l->proficiency_level}", ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
            }
            $section->addTextBreak(1);
        }

        // Save to a temp file first
        try {
            $tempFile = sys_get_temp_dir() . '/CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '_' . time() . '.docx';
            IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

            if (!file_exists($tempFile)) {
                abort(500, 'Error creating Word file');
            }

            // Send correct headers to force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.docx"');
            header('Content-Length: ' . filesize($tempFile));
            readfile($tempFile);

            // Clean up and exit
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

        // Create Word document
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('ar-SA'));

        // Define styles
        $phpWord->addFontStyle('coverTitleStyle', ['bold' => true, 'size' => 36, 'color' => 'FFFFFF', 'name' => 'Arial']);
        $phpWord->addFontStyle('coverSubtitleStyle', ['italic' => true, 'size' => 24, 'color' => 'FFD700', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishSenderStyle', ['bold' => true, 'size' => 18, 'color' => '4169E1', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishTextStyle', ['size' => 16, 'color' => '333333', 'name' => 'Arial']);
        $phpWord->addFontStyle('wishDateStyle', ['size' => 12, 'color' => 'A9A9A9', 'name' => 'Arial']);
        $phpWord->addFontStyle('signatureLabelStyle', ['italic' => true, 'size' => 12, 'color' => '87CEEB', 'name' => 'Arial']);

        // Cover page
        $cover = $phpWord->addSection([
            'pageSizeW' => 12000,
            'pageSizeH' => 15840,
            'marginTop' => 800,
            'marginBottom' => 800,
            'marginLeft' => 1200,
            'marginRight' => 1200,
            'differentFirstPageHeaderFooter' => true,
        ]);

        // Cover content
        $cover->addText('🎓 تهانينا بالتخرج 🎓', 'coverTitleStyle', ['alignment' => Jc::CENTER]);
        $cover->addTextBreak(2);
        $cover->addText('دفتر الأمنيات والتهاني للخريج', 'coverSubtitleStyle', ['alignment' => Jc::CENTER]);
        $cover->addTextBreak(10);
        $cover->addText(date('Y'), ['bold' => true, 'size' => 16, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);

        $cover->addPageBreak();

        // Wishes section
        $section = $phpWord->addSection();

        foreach ($wishes as $wish) {
            // Sender name and date
            $section->addText('👤 ' . ($wish->sender_name ?? 'Unknown'), 'wishSenderStyle', ['alignment' => Jc::START]);
            $section->addText(' ' . $wish->created_at->format('Y-m-d'), 'wishDateStyle', ['alignment' => Jc::END]);
            $section->addTextBreak(1);

            // Wish image if exists
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

            // Wish text
            $section->addText($wish->witsh_text ?? '', 'wishTextStyle', ['alignment' => Jc::CENTER]);
            $section->addTextBreak(2);

            // Signature
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

            $section->addPageBreak(); // Each wish on a separate page
        }

        // Output file
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

