<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('student_id');
            $table->string('class_id');
            $table->date('date');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);

            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
