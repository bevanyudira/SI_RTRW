<?php

use App\Models\Rt;
use App\Models\Rw;
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
        Schema::create('keuangans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rt::class)->nullable();
            $table->foreignIdFor(Rw::class)->nullable();
            $table->enum('variance', ['debit', 'credit']);
            $table->integer('value');
            $table->string('image');
            $table->string('notes');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_rws');
    }
};
