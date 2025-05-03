<?php

use App\Models\Rw;
use App\Models\User;
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
        Schema::create('kritik_saran_rws', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rw::class);
            $table->foreignIdFor(User::class);
            $table->text('isi');
            $table->enum('status', ['belum', 'dibaca', 'selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kritik_saran_rws');
    }
};
