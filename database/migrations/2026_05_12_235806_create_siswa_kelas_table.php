<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_kelas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswas')
                ->onDelete('cascade');

            $table->foreignId('ekstrakurikuler_id')
                ->nullable()
                ->constrained('ekstrakurikulers')
                ->onDelete('set null');

            $table->string('tahun_ajaran'); 

            $table->string('kelas'); 

            $table->enum('status', ['aktif', 'lulus'])
                ->default('aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_kelas');
    }
};