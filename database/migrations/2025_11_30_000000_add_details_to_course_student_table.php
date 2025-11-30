<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            if (!Schema::hasColumn('course_student', 'grade')) {
                $table->integer('grade')->nullable();
            }
            if (!Schema::hasColumn('course_student', 'attendance_record')) {
                $table->string('attendance_record')->default(''); 
            }
        });
    }

    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropColumn(['grade', 'attendance_record']);
        });
    }
};