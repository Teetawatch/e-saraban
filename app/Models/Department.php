<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = []; // อนุญาตให้เพิ่มข้อมูลได้ทุกช่อง (Mass Assignment)

    // ความสัมพันธ์: หน่วยงานมีผู้ใช้งานหลายคน
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function receivedRoutes()
    {
        return $this->hasMany(DocumentRoute::class, 'to_department_id');
    }

    public function sequences()
    {
        return $this->hasMany(DepartmentSequence::class);
    }
}