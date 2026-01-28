<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('department_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->integer('year'); // Buddhist Year (e.g., 2567)
            $table->string('type'); // 'send', 'receive'
            $table->integer('current_number')->default(0);
            $table->boolean('is_locked')->default(false); // True if initial number is set or used
            $table->timestamps();

            $table->unique(['department_id', 'year', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_sequences');
    }
};
