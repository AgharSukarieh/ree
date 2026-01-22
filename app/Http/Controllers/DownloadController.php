<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

        // Fetch education data (if education table exists)
        $education = \DB::table('education')
            ->where('qr_id', $qr_id)
            ->get();

        // Prepare HTML content
        $html = '<style>
            body { font-family: Arial; font-size: 12pt; }
            h1, h2 { color: #000; border-bottom: 1px solid #000; padding-bottom: 4px; }
            .center { text-align: center; }
            .contact span { margin-right: 10px; display: inline-block; vertical-align: middle; }
            .section { margin-top: 9px; }
            hr { border: 1px solid #000; }
            ul { padding-left: 20px; }
            .section p { margin: 4px 0; line-height: 1.4; }
        </style>';

        $html .= '<div class="center"><h1>' . strtoupper(htmlspecialchars($user->name)) . '</h1>';
        $html .= '<h4>' . htmlspecialchars($user->job_title ?? '') . '</h4>';  
        $html .= '</div>';

        $html .= '<div class="contact center" style="line-height:1.8;">';

        if ($user->city) {
            $html .= "<span>📍 {$user->city}</span>";
        }

        if ($user->phone) {
            $html .= "<span>📞 {$user->phone}</span>";
        }

        if ($user->linkedin_profile) {
            $html .= "<span>💼 <a href='{$user->linkedin_profile}' target='_blank'>LinkedIn</a></span>";
        }

        if ($user->github_profile) {
            $html .= "<span>💻 <a href='{$user->github_profile}' target='_blank'>GitHub</a></span>";
        }

        if ($user->email) {
            $html .= "<span>✉️ <a href='mailto:{$user->email}'>{$user->email}</a></span>";
        }

        if ($user->profile_website) {
            $html .= "<span>🌐 <a href='{$user->profile_website}' target='_blank'>Profile</a></span><br/>";
        }

        $html .= '</div>';

        if ($user->profile_summary) {
            $html .= '<div class="section"><h2>Professional Summary</h2>';
            $html .= '<p>' . nl2br(htmlspecialchars($user->profile_summary)) . '</p></div>';
        }

        if ($education->count() > 0) {
            $html .= '<div class="section"><h2>Education</h2><ul>';
            foreach ($education as $edu) {
                $endYear = $edu->end_year == 0 ? 'Present' : $edu->end_year;
                $html .= "<li><strong>{$edu->degree}</strong> – {$edu->university} ({$edu->start_year} - {$endYear})</li>";
            }
            $html .= '</ul></div>';
        }

        if ($user->projects->count() > 0) {
            $html .= '<div class="section"><h2>Projects</h2>';
            foreach ($user->projects as $proj) {
                $html .= "<p><strong>{$proj->project_title}</strong><br/>";
                $html .= nl2br(htmlspecialchars($proj->description ?? '')) . '';
                if ($proj->technologies_used) {
                    $html .= "Technologies Used: <em>{$proj->technologies_used}</em><br/>";
                }
                if ($proj->link) {
                    $html .= "<a href='https://{$proj->link}' target='_blank'>{$proj->link}</a>";
                }
                $html .= '</p>';
            }
            $html .= '</div>';
        }

        // Technical Skills
        if ($user->skills->count() > 0) {
            $html .= '<div class="section"><h2>Technical Skills</h2>';
            foreach ($user->skills->groupBy('category.category_name') as $categoryName => $skills) {
                if ($categoryName) {
                    $html .= "<p><strong>{$categoryName}:</strong> ";
                    $skillNames = $skills->pluck('skill_name')->toArray();
                    $html .= implode(', ', array_map('htmlspecialchars', $skillNames));
                    $html .= '</p>';
                } else {
                    // If no category, just list skills
                    foreach ($skills as $skill) {
                        $html .= '<p>• ' . htmlspecialchars($skill->skill_name) . '</p>';
                    }
                }
            }
            $html .= '</div>';
        }

        if ($user->softSkills->count() > 0) {
            $html .= '<div class="section"><h2>Soft Skills</h2><ul>';
            foreach ($user->softSkills as $soft) {
                $html .= '<li>' . htmlspecialchars($soft->soft_name) . '</li>';
            }
            $html .= '</ul></div>';
        }

        if ($user->certifications->count() > 0) {
            $html .= '<div class="section"><h2>Certifications</h2><ul>';
            foreach ($user->certifications as $c) {
                $issueDate = $c->issue_date ? date('M Y', strtotime($c->issue_date)) : '';
                $html .= "<li>{$c->certifications_name} – {$c->issuing_org}, {$issueDate}</li>";
            }
            $html .= '</ul></div>';
        }

        if ($user->languages->count() > 0) {
            $html .= '<div class="section"><h2>Languages</h2><ul>';
            foreach ($user->languages as $l) {
                $html .= "<li>{$l->language_name} – {$l->proficiency_level}</li>";
            }
            $html .= '</ul></div>';
        }

        // Generate PDF and output
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.pdf', 'D');
        exit;
    }

    /**
     * Generate and download Word CV
     */
    public function generateWord($qr_id)
    {
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

        // Fetch education data
        $education = \DB::table('education')
            ->where('qr_id', $qr_id)
            ->get();

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
        $tempFile = sys_get_temp_dir() . '/CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.docx';
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

        // Send correct headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . basename($tempFile) . '"');
        header('Content-Length: ' . filesize($tempFile));
        readfile($tempFile);

        // Clean up and exit
        @unlink($tempFile);
        exit;
    }

    /**
     * Generate and download wishes book (Word document)
     */
    public function generateWishes($qr_id)
    {
        $user = User::with('wishes')->find($qr_id);

        if (!$user) {
            abort(404, 'User not found');
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
        $filename = "دفتر_التهاني_والأمنيات_{$qr_id}.docx";
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("php://output");
        exit;
    }
}

