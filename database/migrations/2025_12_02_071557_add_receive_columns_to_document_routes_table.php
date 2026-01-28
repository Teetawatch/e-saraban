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
        Schema::table('document_routes', function (Blueprint $table) {
            $table->string('receive_no')->nullable()->after('note');
            $table->date('receive_date')->nullable()->after('receive_no');
        });
    }

    public function down(): void
    {
        Schema::table('document_routes', function (Blueprint $table) {
            $table->dropColumn(['receive_no', 'receive_date']);
        });
    }
};
