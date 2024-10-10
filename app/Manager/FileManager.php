<?php

namespace App\Manager;

use Illuminate\Support\Facades\Log;

class FileManager
{
    public const  OTP_TEXT = 'otp.txt';
    public const  SMS_TEXT = 'otp.txt';

    public static function createFile(string $content, string $type = self::OTP_TEXT): void
    {
        try {
            $file = public_path($type);
            file_put_contents($file, $content);
        } catch (\Throwable $e) {
            Log::info('OTP_WRITE_FAILED', ['data' => $e]);
        }
    }
}
