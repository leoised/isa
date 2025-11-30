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
        Schema::table('course_student', function (Blueprint $table) {
            // Stores numeric grade (0-100)
            $table->integer('grade')->nullable();
            
            // Stores attendance string (e.g., "AAXAA")
            // A = Attended, X = Absent
            $table->string('attendance_record')->default(''); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropColumn(['grade', 'attendance_record']);
        });
    }
};