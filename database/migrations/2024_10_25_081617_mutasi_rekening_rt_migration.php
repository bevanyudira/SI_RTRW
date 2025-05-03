<?php

use App\Models\RekeningRt;
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
        Schema::create('mutasi_rekening_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RekeningRt::class);
            $table->enum('jenis', ['debit', 'kredit']);
            $table->float('jumlah');
            $table->float('saldo_awal');
            $table->float('saldo_akhir');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_rekening_rts');
    }
};
