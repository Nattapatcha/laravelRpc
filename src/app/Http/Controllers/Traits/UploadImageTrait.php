<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadImageTrait
{
    /**
     * อัปโหลดไฟล์จากฟอร์ม image_1..image_5 ไปยัง disk 'public'
     * @param Request $request
     * @param string  $dir โฟลเดอร์ย่อยใน public disk (เช่น 'uploads' หรือ 'uploads/pesticides')
     * @return array  รายการพาธสัมพัทธ์ที่บันทึกลง DB
     */
    protected function uploadFiles(Request $request, string $dir = 'uploads'): array
    {
        $paths = [];
        foreach (range(1, 5) as $i) {
            $key = "image_$i";
            if ($request->hasFile($key)) {
                // ได้พาธสัมพัทธ์ เช่น 'uploads/pesticides/xxxx.png'
                $paths[] = $request->file($key)->store($dir, 'public');
            }
        }
        return $paths;
    }
}
