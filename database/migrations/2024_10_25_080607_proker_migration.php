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
        Schema::create('prokers', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->time('waktu');
            $table->date('tanggal_pelaksanaan');
            $table->string('lokasi');
            $table->string('gambar');
            $table->enum('status', ['on_progress', 'selesai'])->default('on_progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prokers');
    }
};
