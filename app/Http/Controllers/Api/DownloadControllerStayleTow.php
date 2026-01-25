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

        // Prepare HTML with the high-end modern design
        $html = $this->buildModernATSHtml($user, $education);

        // Generate PDF using MPDF
        try {
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 10,
                'margin_right' => 10,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'tempDir' => sys_get_temp_dir(),
                'default_font' => 'arial'
            ]);
            
            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullpage');
            
            $mpdf->WriteHTML($html);
            
            $fileName = 'CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user->name) . '.pdf';
            return $mpdf->Output($fileName, 'D');
            
        } catch (\Exception $e) {
            abort(500, 'Error generating PDF: ' . $e->getMessage());
        }
    }

    /**
     * Build Modern, High-End, ATS-Optimized HTML
     * Layout: Two-column structure with professional blue accents.
     */
    private function buildModernATSHtml($user, $education)
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
                <div class="name">' . htmlspecialchars($user->name) . '</div>
                <div class="summary">
                    ' . nl2br(htmlspecialchars($user->profile_summary)) . '
                </div>
            </div>
            
            <!-- Main Column -->
            <div class="main-content">
                <div class="section-title">Work Experience</div>';

        foreach ($user->experiences as $exp) {
            $html .= '
                <div class="item">
                    <div class="item-header">' . htmlspecialchars($exp->title) . '</div>
                    <div class="item-sub">' . htmlspecialchars($exp->company) . '</div>
                    <div class="item-date">' . htmlspecialchars($exp->start_date) . ' - ' . ($exp->end_date ?: 'Present') . '</div>
                    <ul class="bullet-list">';
            
            $responsibilities = explode("\n", $exp->responsibilities);
            foreach ($responsibilities as $res) {
                if (trim($res)) {
                    $html .= '<li class="bullet-item">' . htmlspecialchars(trim($res, "•- ")) . '</li>';
                }
            }
            
            $html .= '</ul>
                </div>';
        }

        $html .= '
                <div class="section-title">Educational Background</div>';

        foreach ($education as $edu) {
            $html .= '
                <div class="item">
                    <div class="item-header">' . htmlspecialchars($edu->degree) . '</div>
                    <div class="item-sub">' . htmlspecialchars($edu->institution) . '</div>
                    <div class="item-date">' . htmlspecialchars($edu->start_year) . ' - ' . htmlspecialchars($edu->end_year) . '</div>
                </div>';
        }

        $html .= '
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <div class="sidebar-section">
                    <div class="sidebar-title">Contact</div>
                    <div class="contact-info">' . htmlspecialchars($user->city) . '</div>
                    <div class="contact-info">' . htmlspecialchars($user->phone) . '</div>
                    <div class="contact-info">' . htmlspecialchars($user->email) . '</div>';
        
        if ($user->linkedin_profile) {
            $cleanLinkedin = str_replace(['https://', 'www.', 'linkedin.com/in/'], '', $user->linkedin_profile);
            $html .= '<div class="contact-info">' . htmlspecialchars(rtrim($cleanLinkedin, '/')) . '</div>';
        }
        
        $html .= '</div>

                <div class="sidebar-section">
                    <div class="sidebar-title">Skills</div>';
        
        if ($user->skills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-bottom:3pt;">Technical Skills</div>
                      <ul class="skill-list">';
            foreach ($user->skills->take(10) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        if ($user->softSkills->count() > 0) {
            $html .= '<div style="font-weight:bold; font-size:9pt; margin-top:8pt; margin-bottom:3pt;">Soft Skills</div>
                      <ul class="skill-list">';
            foreach ($user->softSkills->take(6) as $skill) {
                $html .= '<li class="skill-item">' . htmlspecialchars($skill->skill_name) . '</li>';
            }
            $html .= '</ul>';
        }

        $html .= '</div>

                <div class="sidebar-section">
                    <div class="sidebar-title">Languages</div>
                    <ul class="skill-list">';
        foreach ($user->languages as $lang) {
            $html .= '<li class="skill-item">' . htmlspecialchars($lang->language_name) . ' (' . htmlspecialchars($lang->proficiency) . ')</li>';
        }
        $html .= '</ul>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-title">Certifications</div>
                    <ul class="skill-list">';
        foreach ($user->certifications as $cert) {
            $html .= '<li class="skill-item" style="margin-bottom:4pt;">' . htmlspecialchars($cert->certificate_name) . '</li>';
        }
        $html .= '</ul>
                </div>
            </div>
            
            <div class="clearfix"></div>
        </div>';

        return $html;
    }
}
