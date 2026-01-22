<?php
// No blank lines or spaces before this tag!
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/connect.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\ListItem;

// 1. Validate QR ID
$qr_id = trim($_GET['id'] ?? '');
if (!preg_match('/^[a-zA-Z0-9\-_]{8,20}$/', $qr_id)) {
    http_response_code(400);
    exit('Invalid QR ID');
}

// 2. Fetch user data
$stmt = $pdo->prepare(
    "SELECT name,
           COALESCE(email,'')       AS email,
           COALESCE(linked_in,'')   AS linkedin,
           COALESCE(github,'')      AS github,
           COALESCE(about_you,'')   AS summary,
           COALESCE(phone,'')       AS phone,
           COALESCE(city,'')        AS city,
           COALESCE(protofile,'')   AS protofile
           
    FROM users
    WHERE qr_id = ?"
);
$stmt->execute([$qr_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit('User not found');
}

// 3. Fetch related sections
$sqlMap = [
    'education'      => "SELECT degree, university, start_year,
                                IF(end_year=0,'Present',end_year) AS end_year
                         FROM education WHERE qr_id = ?",
    'projects' => "SELECT project_title,
                      description,
                      COALESCE(technologis_used,'')    AS technologis_used,
                      COALESCE(link,'')                 AS link
               FROM projects
               WHERE qr_id = ?",
    'skills'         => "SELECT front_end_technologies,
       api_integration,
       tools_platforms,
       operating_systems,
       development_methodologies,
       testing_debugging,
       state_management,
       other_skills


FROM skills WHERE qr_id = ?",
    'certifications' => "SELECT certifications_name, issuing_org,
                                DATE_FORMAT(issue_date,'%b %Y') AS issue_date
                         FROM certifications WHERE qr_id = ?",
    'languages'      => "SELECT language_name, proficiency_level
                         FROM languages WHERE qr_id = ?"
];
$data = [];
foreach ($sqlMap as $key => $sql) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$qr_id]);
    $data[$key] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 4. Build the Word document
$phpWord = new PhpWord();
$phpWord->setDefaultFontName('Arial');
$phpWord->setDefaultFontSize(12);  // Updated for ATS compatibility

$phpWord->addFontStyle('headerTitle', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
$phpWord->addFontStyle('contactLine', ['size' => 9, 'color' => '000000'], ['alignment' => Jc::CENTER]);  // Black text
$phpWord->addFontStyle('sectionTitle', ['bold' => true, 'size' => 12, 'color' => '000000']);  // Smaller, black

$listNumber = ['listType' => ListItem::TYPE_NUMBER_NESTED];
$listBullet = ['listType' => ListItem::TYPE_BULLET_FILLED, 'indent' => 576];

$section = $phpWord->addSection([
    'marginTop' => 600,
    'marginBottom' => 600,
    'marginLeft' => 800,
    'marginRight' => 800
]);

// Header - Name & Job Title
$section->addText(
    strtoupper(htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8')),
    ['size' => 36, 'bold' => false],
    ['alignment' => Jc::CENTER]
);

$section->addText(
    'Front End Web Developer',
    ['size' => 20],
    ['alignment' => Jc::CENTER]
);
$section->addText(
    str_repeat('━', 105), // Bold line
    ['bold' => true, 'color' => '000000', 'size' => 8],
    ['alignment' => Jc::CENTER]
);

// Contact Links
$textRun = $section->addTextRun(['alignment' => Jc::CENTER]);

if (!empty($user['city'])) {
    $textRun->addImage(
        __DIR__ . '/../assests/image/location.png',
        ['width' => 12, 'height' => 12, 'marginRight' => 4]
    );
    $textRun->addText(
        $user['city'],
        ['color' => '000000', 'underline' => 'none']  // Black, no underline
    );
    $textRun->addText('     ');
}

if (!empty($user['phone'])) {
    $textRun->addImage(
        __DIR__ . '/../assests/image/phone.png',
        ['width' => 12, 'height' => 12, 'marginRight' => 4]
    );
    $textRun->addText($user['phone'], ['color' => '000000']);
    $textRun->addText('     ');
}

if (!empty($user['linkedin'])) {
    $textRun->addImage(
        __DIR__ . '/../assests/image/linkedin-logo.png',
        ['width' => 12, 'height' => 12, 'marginRight' => 4]
    );
    $textRun->addLink(
        'https://linkedin.com/in/' . htmlspecialchars($user['linkedin'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($user['linkedin'], ENT_QUOTES, 'UTF-8'),
        ['color' => '000000', 'underline' => 'none']
    );

    $textRun->addText('     ');
}

if (!empty($user['github'])) {
    $textRun->addImage(
        __DIR__ . '/../assests/image/github.png',
        ['width' => 12, 'height' => 12, 'marginRight' => 4]
    );
    $textRun->addLink(
        'https://github.com/' . htmlspecialchars($user['github'], ENT_QUOTES, 'UTF-8'),
        htmlspecialchars($user['github'], ENT_QUOTES, 'UTF-8'),
        ['color' => '000000', 'underline' => 'none']
    );
    $textRun->addText('     ');
}

if (!empty($user['email'])) {
    $section->addTextBreak(1);
    $textRun2 = $section->addTextRun(['alignment' => Jc::CENTER]);
    $textRun2->addImage(
        __DIR__ . '/../assests/image/mail.png',
        ['width' => 12, 'height' => 12, 'marginRight' => 4]
    );
    $textRun2->addText(' ');

    $textRun2->addLink(
        'mailto:' . $user['email'],
        $user['email'],
        ['color' => '000000', 'underline' => 'none']
    );
}
$textRun2->addText('         ');

if (!empty($user['protofile'])) {
    $textRun2->addImage(__DIR__ . '/../assests/image/user.png', ['width' => 12, 'height' => 12, 'marginRight' => 4]);
    $textRun2->addText(' ');

    $textRun2->addLink(htmlspecialchars($user['protofile'], ENT_QUOTES, 'UTF-8'), 'Profile', ['color' => '000000', 'underline' => 'none']);
}

$section->addTextBreak(1.5);

// Professional Summary
if ($user['summary']) {
    $section->addText('PROFESSIONAL SUMMARY', 'sectionTitle');
    $section->addText(
        str_repeat('━', 105),
        ['bold' => true, 'color' => '000000', 'size' => 8]
    );
    foreach (explode("\n", $user['summary']) as $line) {
        $section->addText(htmlspecialchars(trim($line), ENT_QUOTES, 'UTF-8'));
    }
    $section->addTextBreak(2);
}

// Education
if (!empty($data['education'])) {
    $section->addText('EDUCATION', 'sectionTitle');
    $section->addText(
        str_repeat('━', 105),
        ['bold' => true, 'color' => '000000', 'size' => 8]
    );
    $section->addTextBreak(1);
    foreach ($data['education'] as $edu) {
        $section->addText(htmlspecialchars($edu['degree'], ENT_QUOTES, 'UTF-8'), ['size' => 12]);
        $section->addText(htmlspecialchars($edu['university'], ENT_QUOTES, 'UTF-8'), ['size' => 11]);

        $endDate = strtotime($edu['end_year']);
        $currentDate = time();
        $year = date('Y', $endDate);

        if ($endDate > $currentDate) {
            $section->addText('Expected ' . htmlspecialchars($year, ENT_QUOTES, 'UTF-8'), ['size' => 11], ['alignment' => Jc::RIGHT]);
        } else {
            $section->addText(htmlspecialchars($year, ENT_QUOTES, 'UTF-8'), ['size' => 11], ['alignment' => Jc::RIGHT]);
        }

        $section->addTextBreak(1);
    }

    $section->addTextBreak(1);
}

// Projects
if ($data['projects']) {
    $section->addText('PROJECTS', 'sectionTitle');
    $section->addText(
        str_repeat('━', 105),
        ['bold' => true, 'size' => 8, 'color' => '000000']
    );
    $section->addTextBreak(1);
    foreach ($data['projects'] as $proj) {
        $section->addText(htmlspecialchars($proj['project_title'], ENT_QUOTES, 'UTF-8'), ['size' => 12]);
        foreach (explode("\n", $proj['description']) as $desc) {
            if (trim($desc)) {
                $section->addText('  ' . htmlspecialchars(trim($desc), ENT_QUOTES, 'UTF-8'), ['size' => 11]);
            }
        }
        if ($proj['technologis_used']) {
            $section->addText('Technologies Used: ' . htmlspecialchars($proj['technologis_used'], ENT_QUOTES, 'UTF-8'), ['size' => 10]);
        }
        if ($proj['link']) {
            $section->addLink("https://{$proj['link']}", $proj['link'], ['underline' => 'none']);
        }
        $section->addTextBreak(1);
    }
}

// Technical Skills
if (!empty($data['skills'][0]) && is_array($data['skills'][0])) {
    $section->addText('TECHNICAL SKILLS', 'sectionTitle');
    $section->addText(str_repeat('━', 105), ['bold' => true, 'size' => 8, 'color' => '000000']);

    $skillsRow = $data['skills'][0]; // الصف الأول فقط
    
    foreach ($skillsRow as $category => $skillsString) {
        if (!empty($skillsString)) {
            // عرض اسم التصنيف مع استبدال _ بمسافة
            $section->addText("🗂️ " . htmlspecialchars(str_replace('_', ' ', $category), ENT_QUOTES, 'UTF-8'), ['bold' => true]);

            // تفكيك النص المفصول بفواصل وعرض كل مهارة في سطر منفصل
            $skills = array_map('trim', explode(',', $skillsString));
            foreach ($skills as $skill) {
                if (!empty($skill)) {
                    $section->addListItem(htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
                }
            }

            $section->addTextBreak(1); // فاصل بين التصنيفات
        }
    }
}


// Soft Skills
$section->addText('SOFT SKILLS', 'sectionTitle');
$section->addText(
    str_repeat('━', 105),
    ['bold' => true, 'size' => 8, 'color' => '000000']
);
foreach (['Problem Solving', 'Adaptability', 'Attention to Detail', 'Team Collaboration'] as $skill) {
    $section->addListItem(htmlspecialchars($skill, ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
}
$section->addTextBreak(1);

// Certifications
if (!empty($data['certifications'])) {
    $section->addText('CERTIFICATIONS', 'sectionTitle');
    $section->addText(
        str_repeat('━', 105),
        ['bold' => true, 'size' => 8, 'color' => '000000']
    );
    foreach ($data['certifications'] as $c) {
        $section->addListItem(htmlspecialchars("{$c['certifications_name']} – {$c['issuing_org']}, {$c['issue_date']}", ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
    }
    $section->addTextBreak(1);
}

// Languages
if (!empty($data['languages'])) {
    $section->addText('LANGUAGES', 'sectionTitle');
    $section->addText(
        str_repeat('━', 105),
        ['bold' => true, 'size' => 8, 'color' => '000000']
    );
    foreach ($data['languages'] as $l) {
        $section->addListItem(htmlspecialchars("{$l['language_name']} – {$l['proficiency_level']}", ENT_QUOTES, 'UTF-8'), 0, null, $listBullet);
    }
    $section->addTextBreak(1);
}

// 5. Save to a temp file first
$tempFile = sys_get_temp_dir() . '/CV_' . preg_replace('/[^\p{L}\p{N}]/u', '_', $user['name']) . '.docx';
IOFactory::createWriter($phpWord, 'Word2007')->save($tempFile);

// 6. Send correct headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . basename($tempFile) . '"');
header('Content-Length: ' . filesize($tempFile));
readfile($tempFile);

// 7. Clean up and exit
@unlink($tempFile);
exit;
