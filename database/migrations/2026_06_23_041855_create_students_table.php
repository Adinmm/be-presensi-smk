<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_kelas');
            $table->string('jurusan');
            $table->string('tingkat');
            $table->timestamps();
        });
        
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nis')->unique();
            $table->string('jenis_kelamin');
            $table->string('nama_lengkap');
            $table->string('no_telepon')->nullable();

            $table->uuid('class_id');

            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('students');
        Schema::dropIfExists('classes');
    }
};
