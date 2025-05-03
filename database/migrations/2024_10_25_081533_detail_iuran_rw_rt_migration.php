<?php

use App\Models\IuranRw;
use App\Models\Rt;
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
        Schema::create('detail_iuran_rw_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IuranRw::class);
            $table->foreignIdFor(Rt::class);
            $table->enum('status', ['belum', 'pending', 'selesai', 'gagal'])->default('belum');
            $table->bigInteger('nomor_rekening');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_iuran_rw_rts');
    }
};
