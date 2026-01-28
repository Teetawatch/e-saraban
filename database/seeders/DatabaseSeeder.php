<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\DocumentType;
use App\Models\UrgencyLevel;
use App\Models\ConfidentialLevel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Master Data: Departments (หน่วยงาน)
        $deptCentral = Department::create(['name' => 'กองกลาง', 'code' => '01']);
        $deptIT = Department::create(['name' => 'ศูนย์เทคโนโลยีสารสนเทศ', 'code' => '02']);
        $deptHR = Department::create(['name' => 'กองการเจ้าหน้าที่', 'code' => '03']);

        // 2. Master Data: Document Types (ประเภทเอกสาร)
        $types = ['หนังสือภายใน', 'หนังสือภายนอก', 'คำสั่ง', 'ประกาศ', 'บันทึกข้อความ', 'ระเบียบ', 'ข้อบังคับ', 'หนังสือรับรอง', 'ข่าวราชนาวี', 'รายงานการประชุม'];
        foreach($types as $t) DocumentType::create(['name' => $t]);

        // 3. Master Data: Urgency Levels (ระดับความเร่งด่วน)
        UrgencyLevel::create(['name' => 'ปกติ', 'color' => 'gray']);
        UrgencyLevel::create(['name' => 'ด่วน', 'color' => 'orange']);
        UrgencyLevel::create(['name' => 'ด่วนมาก', 'color' => 'red']);
        UrgencyLevel::create(['name' => 'ด่วนที่สุด', 'color' => 'red-dark']);

        // 4. Master Data: Confidential Levels (ชั้นความลับ)
        ConfidentialLevel::create(['name' => 'ปกติ', 'color' => 'gray']);
        ConfidentialLevel::create(['name' => 'ลับ', 'color' => 'yellow']);
        ConfidentialLevel::create(['name' => 'ลับมาก', 'color' => 'orange']);
        ConfidentialLevel::create(['name' => 'ลับที่สุด', 'color' => 'red']);

        // 5. Roles (บทบาทผู้ใช้)
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'label' => 'ผู้ดูแลระบบ']);
        $officerRole = Role::firstOrCreate(['name' => 'officer', 'label' => 'เจ้าหน้าที่สารบรรณ']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'label' => 'ผู้บังคับบัญชา']);
        $staffRole = Role::firstOrCreate(['name' => 'staff', 'label' => 'บุคลากรทั่วไป']);

        // 6. Users (ผู้ใช้งานระบบ)
        
        // 6.1 Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@saraban.com',
            'password' => Hash::make('password'),
            'department_id' => $deptIT->id, // Admin สังกัด IT
        ]);
        $admin->roles()->attach($adminRole);

        // 6.2 Officer User (เจ้าหน้าที่สารบรรณ)
        $officer = User::create([
            'name' => 'Suda Officer',
            'email' => 'officer@saraban.com',
            'password' => Hash::make('password'),
            'department_id' => $deptCentral->id, // เจ้าหน้าที่สารบรรณ สังกัดกองกลาง
        ]);
        $officer->roles()->attach($officerRole);

        // 6.3 Manager User (ผอ.กอง)
        $manager = User::create([
            'name' => 'Mana Manager',
            'email' => 'manager@saraban.com',
            'password' => Hash::make('password'),
            'department_id' => $deptHR->id, // ผอ. สังกัด HR
        ]);
        $manager->roles()->attach($managerRole);

        // 6.4 Staff User (พนักงานทั่วไป)
        $staff = User::create([
            'name' => 'Somchai Staff',
            'email' => 'staff@saraban.com',
            'password' => Hash::make('password'),
            'department_id' => $deptCentral->id, // พนักงาน สังกัดกองกลาง
        ]);
        $staff->roles()->attach($staffRole);
    }
}