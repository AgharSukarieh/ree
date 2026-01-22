<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

use Mpdf\Mpdf;

// 1. Validate QR ID
$qr_id = trim($_GET['id'] ?? '');
if (!preg_match('/^[a-zA-Z0-9\-_]{8,20}$/', $qr_id)) {
    http_response_code(400);
    exit('Invalid QR ID');
}

// 2. Fetch user data
$stmt = $pdo->prepare("
    SELECT name,jop_title, COALESCE(email,'') AS email,
           COALESCE(linked_in,'') AS linkedin,
           COALESCE(github,'') AS github,
           COALESCE(about_you,'') AS summary,
           COALESCE(phone,'') AS phone,
           COALESCE(city,'') AS city,
           COALESCE(protofile,'') AS protofile
    FROM users WHERE qr_id = ?
");
$stmt->execute([$qr_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit('User not found');
}

// 3. Fetch related data
$sqlMap = [
    'education' => "SELECT degree, university, start_year,
                           IF(end_year=0,'Present',end_year) AS end_year
                    FROM education WHERE qr_id = ?",
    'projects' => "SELECT project_title, description,
                          COALESCE(technologis_used,'') AS technologis_used,
                          COALESCE(link,'') AS link
                   FROM projects WHERE qr_id = ?",
      'skills'         => "SELECT front_end_technologies,
       api_integration,
       tools_platforms,
       operating_systems,
       development_methodologies,
       testing_debugging,
       state_management,
       other_skills


FROM skills WHERE qr_id = ?", 'soft_skills' => "SELECT soft_name FROM soft_skills WHERE qr_id = ?",
    'certifications' => "SELECT certifications_name, issuing_org,
                                DATE_FORMAT(issue_date,'%b %Y') AS issue_date
                         FROM certifications WHERE qr_id = ?",
    'languages' => "SELECT language_name, proficiency_level FROM languages WHERE qr_id = ?"
];
$data = [];
foreach ($sqlMap as $key => $sql) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$qr_id]);
    $data[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 4. Prepare HTML content
$html = '<style>
    body { font-family: Arial; font-size: 12pt; }
    h1, h2 { color: #000; border-bottom: 1px solid #000; padding-bottom: 4px; }
    .center { text-align: center; }
    .contact span { margin-right: 10px; display: inline-block; vertical-align: middle; }
    .section { margin-top: 9px; }
    hr { border: 1px solid #000; }
    ul { padding-left: 20px; }
    
    .section p {
        margin: 4px 0; 
        line-height: 1.4; 
    }


</style>';

$html .= '<div class="center"><h1>' . strtoupper(htmlspecialchars($user['name'])) . '</h1>';
$html .= '<h4>' . htmlspecialchars($user['jop_title']) . '</h4>';  
$html .= '</div>';

$html .= '<div class="contact center" style="line-height:1.8;">';

if ($user['city']) {
    $html .= "<span><img src='../assests/image/location.png' width='12' height='12' style='vertical-align:middle;' /> {$user['city']  }</span>";
}

if ($user['phone']) {
    $html .= "<span><img src='../assests/image/phone.png' width='12' height='12' style='vertical-align:middle;margin: right 15px;' /> {$user['phone']  }</span>";
}

if ($user['linkedin']) {
    $html .= "<span><img src='../assests/image/linkedin-logo.png' width='12' height='12' style='vertical-align:middle;margin: right 15px;' /> 
              <a href='https://linkedin.com/in/{$user['linkedin']}' target='_blank'>{$user['linkedin']  }</a><br/></span>";
}

if ($user['github']) {
    $html .= "<span><img src='../assests/image/github.png' width='12' height='12' style='vertical-align:middle; margin: right 15px;' /> 
              <a href='https://github.com/{$user['github']  }' target='_blank'>{$user['github']}  </a></span>";
}

if ($user['email']) {
    $html .= "<span><img src='../assests/image/mail.png' width='12' height='12' style='vertical-align:middle;margin: right 15px;' /> 
              <a href='mailto:{$user['email']}'>{$user['email']  }</a></span>";
}

if ($user['protofile']) {
    $html .= "<span><img src='../assests/image/user.png' width='12' height='12' style='vertical-align:middle;margin: right 15px;' /> 
              <a href='{$user['protofile']}' target='_blank'>Profile</a></span><br/>";
}

$html .= '</div>';




if ($user['summary']) {
    $html .= '<div class="section"><h2>Professional Summary</h2>';
    $html .= '<p>' . nl2br(htmlspecialchars($user['summary'])) . '</p></div>';
}

if ($data['education']) {
    $html .= '<div class="section"><h2>Education</h2><ul>';
    foreach ($data['education'] as $edu) {
        $endDate = strtotime($edu['end_year']);
        $currentDate = time();
        $year = date('Y', $endDate);
        
        $displayYear = ($endDate > $currentDate) ? "Expected {$year}" : $year;
        
        $html .= "<li><strong>{$edu['degree']}</strong> – {$edu['university']} ({$displayYear})</li>";
            }
    $html .= '</ul></div>';
} 

if ($data['projects']) {
    $html .= '<div class="section"><h2>Projects</h2>';
    foreach ($data['projects'] as $proj) {
        $html .= "<p><strong>{$proj['project_title']}</strong><br/>";
        $html .= nl2br(htmlspecialchars($proj['description'])) . '';
        if ($proj['technologis_used']) {
            $html .= "Technologies Used: <em>{$proj['technologis_used']}</em><br/>";
        }
        if ($proj['link']) {
            $html .= "<a href='https://{$proj['link']}' target='_blank'>{$proj['link']}</a>";
        }
        $html .= '</p>';
    }
    $html .= '</div>';
} 

// if (!empty($data['skills'])) {
//     $html .= '<div class="section"><h2>Technical Skills</h2>';

//     $skillsRow = $data['skills']; // Single row containing all categories

//     foreach ($skillsRow as $category => $skills) {
//         if (!empty($skills) && is_array($skills)) {
//             $html .= '<p>• ' . htmlspecialchars(str_replace('_', ' ', $category)) . ': ';
//             $html .= implode(', ', array_map('htmlspecialchars', $skills));
//             $html .= '</p>';
//         }
//     }

//     $html .= '</div>';
// }
if (!empty($data['skills'][0])) {
    $html .= '<div class="section"><h2>Technical Skills</h2>';

    $skills = $data['skills'][0];  // الصف الوحيد المتوقع من الجدول

    if (!empty($skills['front_end_technologies'])) {
        $html .= '<p>• Front-End Technologies: ' . htmlspecialchars($skills['front_end_technologies']) . '</p>';
    }

    if (!empty($skills['api_integration'])) {
        $html .= '<p>• API Integration: ' . htmlspecialchars($skills['api_integration']) .'</p>';
    }
    if (!empty($skills['tools_platforms'])) {
        $html .= '<p>• Tools & Platforms: ' . htmlspecialchars($skills['tools_platforms']) . '</p>';
    }

    if (!empty($skills['operating_systems'])) {
        $html .= '<p>• Operating Systems: ' . htmlspecialchars($skills['operating_systems']) . '</p>';
    }

    if (!empty($skills['development_methodologies'])) {
        $html .= '<p>• Development Methodologies: ' . htmlspecialchars($skills['development_methodologies']) . '</p>';
    }

    if (!empty($skills['testing_debugging'])) {
        $html .= '<p>• Testing & Debugging: ' . htmlspecialchars($skills['testing_debugging']) . '</p>';
    }

    if (!empty($skills['state_management'])) {
        $html .= '<p>• State Management: ' . htmlspecialchars($skills['state_management']) . '</p>';
    }

    if (!empty($skills['other_skills'])) {
        $html .= '<p>• Other Skills: ' . htmlspecialchars($skills['other_skills']) . '</p>';
    }

    $html .= '</div>';
}

if (!empty($data['soft_skills'])) {
    $html .= '<div class="section"><h2>Soft Skills</h2><ul>';
    foreach ($data['soft_skills'] as $soft) {
        $html .= '<li>' . htmlspecialchars($soft['soft_name']) . '</li>';
    }
    $html .= '</ul></div>';
}


if ($data['certifications']) {
    $html .= '<div class="section"><h2>Certifications</h2><ul>';
    foreach ($data['certifications'] as $c) {
        $html .= "<li>{$c['certifications_name']} – {$c['issuing_org']}, {$c['issue_date']}</li>";
    }
    $html .= '</ul></div>';
} 

if ($data['languages']) {
    $html .= '<div class="section"><h2>Languages</h2><ul>';
    foreach ($data['languages'] as $l) {
        $html .= "<li>{$l['language_name']} – {$l['proficiency_level']}</li>";
    }
    $html .= '</ul></div>';
} 

// 5. Generate PDF and output
$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output('CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user['name']) . '.pdf', 'D');
exit;
