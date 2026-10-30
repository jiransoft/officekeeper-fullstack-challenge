<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 부서 모델
 *
 * 기존 departments 테이블(mysql/init.sql)과 연동됩니다.
 */
class Department extends Model
{
    protected $fillable = ['name', 'code'];

    public function users()
    {
        return $this->hasMany(User::class, 'dept_id');
    }
}
