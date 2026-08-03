<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Compress and optimize an uploaded image file, then store it to disk.
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $directory Directory inside storage/app/public (e.g., 'covers' or 'profil')
     * @param int $maxWidth Maximum allowed width in pixels
     * @param int $maxHeight Maximum allowed height in pixels
     * @param int $quality Compression quality (1-100)
     * @return string Relative path stored (e.g., 'covers/abc123xyz.jpg')
     */
    public static function optimizeAndStore(
        UploadedFile $file,
        string $directory = 'covers',
        int $maxWidth = 1000,
        int $maxHeight = 1200,
        int $quality = 80
    ): string {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::random(40) . '.jpg'; // Convert output to optimized JPG for maximum compatibility
        $targetDirectoryPath = storage_path('app/public/' . trim($directory, '/'));

        if (! file_exists($targetDirectoryPath)) {
            mkdir($targetDirectoryPath, 0755, true);
        }

        $targetFilePath = $targetDirectoryPath . '/' . $filename;
        $realPath = $file->getRealPath();

        // Attempt optimization using GD library
        $optimized = self::processWithGd($realPath, $targetFilePath, $maxWidth, $maxHeight, $quality);

        if ($optimized) {
            return trim($directory, '/') . '/' . $filename;
        }

        // Fallback to default Laravel storage if GD fails
        $path = $file->store($directory, 'public');
        return $path;
    }

    /**
     * Compress and fix an existing file on disk.
     */
    public static function optimizeExistingFile(string $fullPath, int $maxWidth = 1000, int $maxHeight = 1200, int $quality = 80): bool
    {
        if (! file_exists($fullPath) || ! is_file($fullPath)) {
            return false;
        }

        return self::processWithGd($fullPath, $fullPath, $maxWidth, $maxHeight, $quality);
    }

    /**
     * Process image with GD: Fix orientation, resize, and compress.
     */
    private static function processWithGd(
        string $sourcePath,
        string $destinationPath,
        int $maxWidth,
        int $maxHeight,
        int $quality
    ): bool {
        if (! extension_loaded('gd')) {
            return false;
        }

        $imageInfo = @getimagesize($sourcePath);
        if (! $imageInfo) {
            return false;
        }

        $mime = $imageInfo['mime'] ?? '';
        $srcImage = null;

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $srcImage = @function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null;
                break;
            case 'image/gif':
                $srcImage = @imagecreatefromgif($sourcePath);
                break;
        }

        if (! $srcImage) {
            return false;
        }

        // Auto rotate based on EXIF orientation for JPEGs
        if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || $mime === 'image/jpg')) {
            $exif = @exif_read_data($sourcePath);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $srcImage = imagerotate($srcImage, 180, 0);
                        break;
                    case 6:
                        $srcImage = imagerotate($srcImage, -90, 0);
                        break;
                    case 8:
                        $srcImage = imagerotate($srcImage, 90, 0);
                        break;
                }
            }
        }

        $origWidth = imagesx($srcImage);
        $origHeight = imagesy($srcImage);

        if ($origWidth <= 0 || $origHeight <= 0) {
            imagedestroy($srcImage);
            return false;
        }

        // Calculate target dimensions keeping aspect ratio
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);

        // Don't upscale small images
        if ($ratio > 1.0) {
            $ratio = 1.0;
        }

        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Fill background with white for transparent images
        $white = imagecolorallocate($dstImage, 255, 255, 255);
        imagefill($dstImage, 0, 0, $white);

        // Resample image smoothly
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Output as high quality compressed JPEG
        $success = @imagejpeg($dstImage, $destinationPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $success;
    }
}
