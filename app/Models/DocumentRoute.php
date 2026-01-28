<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRoute extends Model
{
    use HasFactory;

    protected $guarded = [];

    // เอกสารที่เกี่ยวข้อง
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // ผู้ส่ง
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    // ผู้รับ (กรณีระบุคน)
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    // ผู้รับ (กรณีระบุหน่วยงาน)
    public function toDepartment()
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }
}