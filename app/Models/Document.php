<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = []; // อนุญาตให้ใส่ข้อมูลได้ทุกช่อง (Mass Assignment)

    // Relationships
    public function type() {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function urgency() {
        return $this->belongsTo(UrgencyLevel::class, 'urgency_level_id');
    }

    public function confidential() {
        return $this->belongsTo(ConfidentialLevel::class, 'confidential_level_id');
    }

    public function user() {
        return $this->belongsTo(User::class); // ผู้สร้าง
    }
    
    public function department() {
        return $this->belongsTo(Department::class); // หน่วยงานต้นเรื่อง
    }

    public function attachments() {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function routes() {
        return $this->hasMany(DocumentRoute::class);
    }

    public function folder() {
        return $this->belongsTo(Folder::class);
    }

    /**
     * Scope สำหรับกรองเอกสารที่ User คนนี้มีสิทธิ์เห็น
     * 1. เป็นคนสร้าง หรือ คนในหน่วยงานเดียวกันสร้าง (Internal/Outbox)
     * 2. ถูกส่งมาหาต้วเอง หรือ ส่งมาหาหน่วยงานตัวเอง (Inbox)
     */
    public function scopeAccessibleBy($query, $user)
    {
        return $query->where(function($q) use ($user) {
            // 1. Own Department Created (Outbox / Internal)
            $q->where('department_id', $user->department_id)
            
            // 2. Sent to Me or My Department (Inbox)
              ->orWhereHas('routes', function($r) use ($user) {
                  $r->where('to_department_id', $user->department_id)
                    ->orWhere('to_user_id', $user->id);
              });
        });
    }
}