<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    public $timestamps = false;              // ตารางนี้ไม่มี timestamps
    protected $fillable = ['name'];
    protected $casts = ['id' => 'integer'];

    // 1 หน่วยงาน มีผู้ใช้หลายคน
    public function users()
    {
        // FK ใน users คือคอลัมน์ 'organization'
        return $this->hasMany(User::class, 'organization', 'id');
    }
}
