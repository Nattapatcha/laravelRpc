<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // ประเภทผู้ใช้
    public const TYPE_AGRICULTURIST = 1; // เกษตรกร
    public const TYPE_EMPLOYEE = 2; // พนักงาน

    // อนุญาตกรอก
    protected $fillable = ['name', 'surname', 'email', 'password', 'type', 'organization', 'idcard', 'status'];

    // ซ่อนเวลา serialize
    protected $hidden = ['password', 'remember_token'];

    // แคสต์
    protected $casts = [
        'email_verified_at' => 'datetime',
        'type' => 'integer',
        'organization' => 'integer',
    ];

    /** กรองพนักงาน */
    public function scopeEmployees($q)
    {
        return $q->where('type', self::TYPE_EMPLOYEE)
            ->select(['id', 'name', 'email']); // ใช้เฉพาะ name/email
    }

    /** กรองเกษตรกร */
    public function scopeAgriculturists($q)
    {
        return $q->where('type', self::TYPE_AGRICULTURIST)
            ->select(['id', 'name', 'email']);
    }

    /** ค้นหาเฉพาะชื่อ/อีเมล */
    public function scopeSearchNameEmail($q, ?string $kw)
    {
        $kw = trim((string) $kw);
        if ($kw === '')
            return $q;

        return $q->where(function ($qq) use ($kw) {
            $qq->where('name', 'like', "%{$kw}%")
                ->orWhere('email', 'like', "%{$kw}%");
        });
    }
    public function getFullNameAttribute(): string
    {
        return trim(($this->name ?? '') . ' ' . ($this->surname ?? ''));
    }
    public function org()
    {
        // users.organization -> organizations.id
        return $this->belongsTo(Organization::class, 'organization', 'id');
    }

    // (ออปชัน) เรียกชื่อหน่วยงานง่าย ๆ: $user->organization_name
    public function getOrganizationNameAttribute(): ?string
    {
        return $this->org->name ?? null;
    }
}