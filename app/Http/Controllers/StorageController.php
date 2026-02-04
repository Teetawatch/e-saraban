<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $path): StreamedResponse
    {
        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // ดึงไฟล์และส่งกลับพร้อม MIME Type ที่ถูกต้อง
        return Storage::disk('public')->response($path);
    }
}
