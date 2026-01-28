<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ตารางหน่วยงาน
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อหน่วยงาน
            $table->string('code')->nullable(); // รหัสหน่วยงาน (ถ้ามี)
            $table->timestamps();
        });

        // 2. ตารางประเภทเอกสาร (หนังสือภายใน, ภายนอก, คำสั่ง ฯลฯ)
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 3. ตารางระดับความเร่งด่วน (ปกติ, ด่วน, ด่วนที่สุด)
        Schema::create('urgency_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable(); // เก็บ code สี (เช่น #ef4444) ไว้ใช้แสดงผล
            $table->timestamps();
        });

        // 4. ตารางชั้นความลับ (ปกติ, ลับ, ลับมาก)
        Schema::create('confidential_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('confidential_levels');
        Schema::dropIfExists('urgency_levels');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('departments');
    }
};