<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 사용자 모델
 *
 * 기존 users 테이블(mysql/init.sql)과 연동됩니다.
 * 이 모델은 제공된 스캐폴딩입니다. 필요에 따라 수정하세요.
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'user_name',
        'password',
        'role',
        'dept_id',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * 사용자의 부서
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * 관리자 여부 확인
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 매니저 이상 권한 확인
     */
    public function isManagerOrAbove(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }
}
