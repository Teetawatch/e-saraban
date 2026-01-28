<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ตารางเอกสารหลัก (Header)
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_no'); // เลขที่หนังสือ
            $table->string('title'); // เรื่อง
            $table->date('document_date')->nullable(); // ลงวันที่
            
            // FK ต่างๆ
            $table->foreignId('document_type_id')->constrained();
            $table->foreignId('urgency_level_id')->constrained();
            $table->foreignId('confidential_level_id')->constrained();
            
            $table->foreignId('user_id')->constrained(); // ผู้สร้างเอกสาร
            $table->foreignId('department_id')->constrained(); // หน่วยงานเจ้าของเรื่อง

            $table->string('status')->default('draft'); // draft, sent, received, closed
            $table->timestamps();
            $table->softDeletes(); // ไม่ลบจริง (สำคัญสำหรับงานเอกสาร)
        });

        // 2. ตารางไฟล์แนบ
        Schema::create('document_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            $table->string('file_name'); // ชื่อไฟล์เดิม
            $table->string('file_path'); // path ที่เก็บใน server
            $table->string('file_type')->nullable(); // pdf, jpg
            $table->integer('file_size')->nullable();
            $table->timestamps();
        });

        // 3. ตารางเส้นทางเดินเอกสาร (History Log / Routing)
        Schema::create('document_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->cascadeOnDelete();
            
            $table->foreignId('from_user_id')->nullable()->constrained('users');
            $table->foreignId('to_user_id')->nullable()->constrained('users'); // ส่งหาคน (ถ้ามี)
            $table->foreignId('to_department_id')->nullable()->constrained('departments'); // ส่งหาหน่วยงาน (ถ้ามี)
            
            $table->string('action'); // send, receive, comment, approve
            $table->text('note')->nullable(); // บันทึกข้อความ/เกษียนหนังสือ
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_routes');
        Schema::dropIfExists('document_attachments');
        Schema::dropIfExists('documents');
    }
};