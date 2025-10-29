<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
/**
 * Class Media.
 *
 * @package namespace App\Entities;
 */
class Media extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['model', 'model_id', 'image'];

    protected $hidden = ['model', 'model_id'];

    public $timestamps = false;
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image)
            return null;

        $path = ltrim(preg_replace('#^(public/|storage/)#', '', (string) $this->image), '/');

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        // ไฟล์อยู่ใน storage/app/public ?
        if ($disk->exists($path)) {
            return $disk->url($path); // /storage/...
        }

        // legacy: เผื่อไฟล์เก่าเคยอยู่ใน public/
        if (file_exists(public_path($path))) {
            return asset($path); // /uploads/...
        }

        // หาไม่เจอ → ให้ null เพื่อให้ Blade แสดง placeholder
        return null;
    }
}
