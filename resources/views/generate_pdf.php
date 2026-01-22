<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/connect.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

// التحقق من qr_id
$qr_id = $_GET['id'] ?? null;
if (!$qr_id) {
    die('QR ID غير موجود');
}

// جلب بيانات المستخدم
$userStmt = $pdo->prepare("SELECT * FROM users WHERE qr_id = ?");
$userStmt->execute([$qr_id]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('المستخدم غير موجود');
}

$backgroundImage = $user['image'] ?? null;

// جلب الأمنيات
$stmt = $pdo->prepare("SELECT * FROM wishes WHERE qr_id = ? ORDER BY created_at DESC");
$stmt->execute([$qr_id]);
$wishes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// إنشاء مستند Word
$phpWord = new PhpWord();
$phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('ar-SA'));

// تعريف الأنماط
$phpWord->addFontStyle('coverTitleStyle', ['bold' => true, 'size' => 36, 'color' => 'FFFFFF', 'name' => 'Arial']);
$phpWord->addFontStyle('coverSubtitleStyle', ['italic' => true, 'size' => 24, 'color' => 'FFD700', 'name' => 'Arial']);
$phpWord->addFontStyle('wishSenderStyle', ['bold' => true, 'size' => 18, 'color' => '4169E1', 'name' => 'Arial']);
$phpWord->addFontStyle('wishTextStyle', ['size' => 16, 'color' => '333333', 'name' => 'Arial']);
$phpWord->addFontStyle('wishDateStyle', ['size' => 12, 'color' => 'A9A9A9', 'name' => 'Arial']);
$phpWord->addFontStyle('signatureLabelStyle', ['italic' => true, 'size' => 12, 'color' => '87CEEB', 'name' => 'Arial']);

// صفحة الغلاف
$cover = $phpWord->addSection([
    'pageSizeW' => 12000,
    'pageSizeH' => 15840,
    'marginTop' => 800,
    'marginBottom' => 800,
    'marginLeft' => 1200,
    'marginRight' => 1200,
    'differentFirstPageHeaderFooter' => true,
]);

// صورة الخلفية إن وجدت
if ($backgroundImage) {
    $bgPath = realpath(__DIR__ . '/' . $backgroundImage);
    if ($bgPath && file_exists($bgPath)) {
        $cover->addImage($bgPath, [
            'width' => 600,
            'height' => 900,
            'positioning' => 'absolute',
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
            'wrappingStyle' => 'behind',
            'marginTop' => 0,
            'marginLeft' => 0,
        ]);
    }
}

// محتوى الغلاف
$cover->addText('🎓 تهانينا بالتخرج 🎓', 'coverTitleStyle', ['alignment' => Jc::CENTER]);
$cover->addTextBreak(2);
$cover->addText('دفتر الأمنيات والتهاني للخريج', 'coverSubtitleStyle', ['alignment' => Jc::CENTER]);
$cover->addTextBreak(10);
$cover->addText(date('Y'), ['bold' => true, 'size' => 16, 'color' => 'FFFFFF'], ['alignment' => Jc::CENTER]);

$cover->addPageBreak();

// قسم الأمنيات
$section = $phpWord->addSection();

foreach ($wishes as $wish) {
 // اسم المرسل والتاريخ
 $section->addText('👤 ' . $wish['sender_name'], 'wishSenderStyle', ['alignment' => Jc::START]);
 $section->addText(' ' . $wish['created_at'], 'wishDateStyle', ['alignment' => Jc::END]);
 $section->addTextBreak(1);
    // صورة المُرسل
    if (!empty($wish['image'])) {
        $imagePath = realpath(__DIR__ . '/../' . $wish['image']);
        if ($imagePath && file_exists($imagePath)) {
            $section->addImage($imagePath, [
                'width' => 400,
                'height' => 400,
                'alignment' => Jc::CENTER
            ]);
        }
    }

    $section->addTextBreak(2);

    // نص الأمنية
    $section->addText($wish['witsh_text'], 'wishTextStyle', ['alignment' => Jc::CENTER]);
    $section->addTextBreak(2);

   

    // التوقيع
    if (!empty($wish['singnature'])) {
        $signaturePath = realpath(__DIR__ . '/../' . $wish['singnature']);
        if ($signaturePath && file_exists($signaturePath)) {
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

    $section->addPageBreak(); // كل أمنية في صفحة
}

// إخراج الملف
$filename = "دفتر_التهاني_والأمنيات_$qr_id.docx";
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save("php://output");
exit;
