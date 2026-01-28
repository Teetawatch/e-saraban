<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // ใครทำ (ถ้าลบ user ให้เป็น null)
            $table->string('action'); // ทำอะไร (login, create, view, download, update, delete)
            $table->string('module'); // ทำกับโมดูลไหน (auth, document, user)
            $table->string('resource_id')->nullable(); // ID ของข้อมูลที่ถูกกระทำ (เช่น document_id)
            $table->text('description')->nullable(); // รายละเอียดเพิ่มเติม (เช่น แก้ไขเรื่องจาก A เป็น B)
            $table->string('ip_address')->nullable(); // IP Address
            $table->string('user_agent')->nullable(); // Browser/Device Info
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};