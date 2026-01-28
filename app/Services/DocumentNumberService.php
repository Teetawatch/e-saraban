<?php

namespace App\Services;

use App\Models\DepartmentSequence;
use Illuminate\Support\Facades\DB;

class DocumentNumberService
{
    /**
     * Get the next running number for sending a document (Document No).
     * Format: Year/Running (e.g., 2567/0001)
     */
    public function getNextSendNumber($departmentId)
    {
        $year = date('Y') + 543;
        $number = $this->incrementAndGetNumber($departmentId, $year, 'send');
        
        // Format: Year/Running (4 digits)
        return $year . '/' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the next running number for receiving a document (Receive No).
     * Format: Running Number (e.g., 1, 2, 3...)
     */
    public function getNextReceiveNumber($departmentId)
    {
        $year = date('Y') + 543;
        return $this->incrementAndGetNumber($departmentId, $year, 'receive');
    }

    /**
     * Core logic to increment and get the number.
     */
    private function incrementAndGetNumber($departmentId, $year, $type)
    {
        return DB::transaction(function () use ($departmentId, $year, $type) {
            // Find or create the sequence record for this year
            $sequence = DepartmentSequence::firstOrCreate(
                [
                    'department_id' => $departmentId,
                    'year' => $year,
                    'type' => $type
                ],
                [
                    'current_number' => 0,
                    'is_locked' => true // Auto-created means it's now in use
                ]
            );

            // Increment safely
            $sequence->current_number++;
            $sequence->save();

            return $sequence->current_number;
        });
    }

    /**
     * Set the initial number for a department (Admin only).
     * Can only set if not locked (i.e., not yet used or explicitly allowed).
     */
    public function setInitialNumber($departmentId, $type, $year, $number)
    {
        $sequence = DepartmentSequence::firstOrNew([
            'department_id' => $departmentId,
            'year' => $year,
            'type' => $type
        ]);

        if ($sequence->exists && $sequence->is_locked) {
            throw new \Exception("ไม่สามารถแก้ไขเลขเริ่มต้นได้เนื่องจากมีการใช้งานไปแล้ว");
        }

        $sequence->current_number = $number;
        $sequence->is_locked = true; // Lock immediately after setting
        $sequence->save();

        return $sequence;
    }
}
