<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    /**
     * رفع صورة واحدة إلى Cloudinary
     * 
     * @param mixed $imageFile ملف الصورة من Request
     * @param string|null $folder مجلد التخزين
     * @param array $options خيارات إضافية
     * @return array ['url' => string, 'public_id' => string]
     * @throws Exception
     */
    public function uploadImage($imageFile, ?string $folder = null, array $options = []): array
    {
        try {
            $uploadOptions = array_merge([
                'transformation' => [
                    ['quality' => 'auto'],
                    ['fetch_format' => 'auto']
                ]
            ], $options);

            // Handle folder and public_id
            if ($folder) {
                $uploadOptions['folder'] = $folder;
                // إذا كان public_id يحتوي على المجلد، أزل المجلد منه
                if (isset($uploadOptions['public_id']) && strpos($uploadOptions['public_id'], $folder . '/') === 0) {
                    $uploadOptions['public_id'] = str_replace($folder . '/', '', $uploadOptions['public_id']);
                }
            }

            // Handle both file objects and file paths
            $filePath = is_string($imageFile) ? $imageFile : $imageFile->getRealPath();
            $result = Cloudinary::uploadApi()->upload($filePath, $uploadOptions);

            // ApiResponse is an ArrayObject, access data as array
            return [
                'url' => $result['secure_url'] ?? $result['url'] ?? '',
                'public_id' => $result['public_id'] ?? '',
                'width' => $result['width'] ?? null,
                'height' => $result['height'] ?? null,
                'format' => $result['format'] ?? '',
                'bytes' => $result['bytes'] ?? 0
            ];
        } catch (Exception $e) {
            Log::error('Cloudinary upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new Exception('فشل رفع الصورة: ' . $e->getMessage());
        }
    }

    /**
     * رفع صورة الملف الشخصي مع تحسينات
     * 
     * @param mixed $imageFile ملف الصورة
     * @param int|null $userId معرف المستخدم
     * @return array
     * @throws Exception
     */
    public function uploadProfileImage($imageFile, ?int $userId = null): array
    {
        $publicId = $userId ? "profiles/user_{$userId}" : null;

        return $this->uploadImage($imageFile, 'profiles', [
            'public_id' => $publicId,
            'overwrite' => true,
            'transformation' => [
                ['width' => 400, 'height' => 400, 'crop' => 'fill'],
                ['quality' => 'auto'],
                ['fetch_format' => 'auto']
            ]
        ]);
    }

    /**
     * حذف صورة من Cloudinary
     * 
     * @param string $publicId معرف الصورة العام
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            Cloudinary::uploadApi()->destroy($publicId);
            return true;
        } catch (Exception $e) {
            Log::error('Cloudinary delete failed', [
                'public_id' => $publicId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * رفع عدة صور
     * 
     * @param array $imageFiles مصفوفة من ملفات الصور
     * @param string|null $folder مجلد التخزين
     * @return array
     */
    public function uploadMultipleImages(array $imageFiles, ?string $folder = null): array
    {
        $results = [];

        foreach ($imageFiles as $imageFile) {
            try {
                $result = $this->uploadImage($imageFile, $folder);
                $results[] = $result;
            } catch (Exception $e) {
                Log::error('Failed to upload image in batch', [
                    'error' => $e->getMessage()
                ]);
                $results[] = ['error' => $e->getMessage()];
            }
        }

        return $results;
    }

    /**
     * الحصول على رابط الصورة مع تحويلات
     * 
     * @param string $publicId معرف الصورة العام
     * @param array $transformations التحويلات
     * @return string
     */
    public function getImageUrl(string $publicId, array $transformations = []): string
    {
        return Cloudinary::image($publicId)
            ->resize(\Cloudinary\Transformation\Resize::fill()->width($transformations['width'] ?? 400)->height($transformations['height'] ?? 400))
            ->format($transformations['format'] ?? 'auto')
            ->quality(\Cloudinary\Transformation\Quality::auto())
            ->toUrl();
    }
}

