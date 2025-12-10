# إعداد Cloudinary لرفع الصور

## 📋 المتطلبات

- حساب Cloudinary
- Cloud Name: `dozvsu2rp`
- API Key: `629249255372626`
- API Secret: `DQATNuq02hWbcKW33DY_xRlnQTI`

## 🔐 إعداد المتغيرات في `.env`

أضف المتغير التالي إلى ملف `.env`:

```env
# Cloudinary Configuration (الطريقة الموصى بها)
CLOUDINARY_URL=cloudinary://629249255372626:DQATNuq02hWbcKW33DY_xRlnQTI@dozvsu2rp
```

**أو** يمكنك استخدام المتغيرات المنفصلة:

```env
# Cloudinary Configuration (طريقة بديلة)
CLOUDINARY_CLOUD_NAME=dozvsu2rp
CLOUDINARY_API_KEY=629249255372626
CLOUDINARY_API_SECRET=DQATNuq02hWbcKW33DY_xRlnQTI
```

## 📝 إضافة إلى `.env.example`

أضف نفس المتغيرات إلى `.env.example` (بدون القيم الحقيقية):

```env
# Cloudinary Configuration
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME

# أو المتغيرات المنفصلة
# CLOUDINARY_CLOUD_NAME=
# CLOUDINARY_API_KEY=
# CLOUDINARY_API_SECRET=
```

## 🚀 كيفية الاستخدام في Laravel

### 1. رفع صورة واحدة

```php
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

// رفع صورة من Request
$uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

// أو رفع من مسار محلي
$uploadedFileUrl = Cloudinary::upload('path/to/image.jpg')->getSecurePath();
```

### 2. رفع صورة مع خيارات

```php
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

$result = Cloudinary::upload($request->file('image')->getRealPath(), [
    'folder' => 'profile_images',
    'transformation' => [
        ['width' => 500, 'height' => 500, 'crop' => 'fill'],
        ['quality' => 'auto']
    ]
]);

$imageUrl = $result->getSecurePath();
$publicId = $result->getPublicId();
```

### 3. حذف صورة

```php
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

Cloudinary::destroy('public_id_of_image');
```

### 4. مثال كامل في Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageController extends Controller
{
    public function uploadProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        try {
            // رفع الصورة
            $result = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'profiles',
                'transformation' => [
                    ['width' => 400, 'height' => 400, 'crop' => 'fill'],
                    ['quality' => 'auto']
                ]
            ]);

            // حفظ الرابط في قاعدة البيانات
            $imageUrl = $result->getSecurePath();
            
            return response()->json([
                'success' => true,
                'image_url' => $imageUrl,
                'public_id' => $result->getPublicId()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل رفع الصورة: ' . $e->getMessage()
            ], 500);
        }
    }
}
```

### 5. استخدام في Model (مثال)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class User extends Model
{
    protected $fillable = ['name', 'profile_image_url'];

    public function uploadProfileImage($imageFile)
    {
        $result = Cloudinary::upload($imageFile->getRealPath(), [
            'folder' => 'profiles',
            'public_id' => 'user_' . $this->id,
            'overwrite' => true,
            'transformation' => [
                ['width' => 400, 'height' => 400, 'crop' => 'fill']
            ]
        ]);

        $this->update([
            'profile_image_url' => $result->getSecurePath()
        ]);

        return $this;
    }
}
```

## 🔍 الوصول إلى الإعدادات

يمكنك الوصول إلى إعدادات Cloudinary من `config/services.php`:

```php
$cloudName = config('services.cloudinary.cloud_name');
$apiKey = config('services.cloudinary.api_key');
$apiSecret = config('services.cloudinary.api_secret');
```

## 📚 المزيد من الأمثلة

### رفع متعدد الملفات

```php
foreach ($request->file('images') as $image) {
    $result = Cloudinary::upload($image->getRealPath(), [
        'folder' => 'gallery'
    ]);
    $urls[] = $result->getSecurePath();
}
```

### تحويل الصورة

```php
$transformedUrl = Cloudinary::image('public_id')
    ->resize(Resize::fill()->width(300)->height(300))
    ->format(Format::webp())
    ->quality(Quality::auto())
    ->toUrl();
```

## ⚠️ ملاحظات أمنية

1. **لا تضع API Secret في الكود** - استخدم دائماً `.env`
2. **أضف `.env` إلى `.gitignore`** - تأكد من عدم رفع ملف `.env` إلى Git
3. **استخدم HTTPS** - استخدم `getSecurePath()` بدلاً من `getPath()` للحصول على رابط HTTPS

## 🔗 روابط مفيدة

- [Cloudinary Laravel Package](https://github.com/cloudinary-labs/cloudinary-laravel)
- [Cloudinary PHP SDK Documentation](https://cloudinary.com/documentation/php_integration)
- [Cloudinary Dashboard](https://cloudinary.com/console)

