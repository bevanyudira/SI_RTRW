<?php

use App\Models\Proker;
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
        Schema::create('detail_proker_rts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Proker::class);
            $table->foreignIdFor(Rt::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_proker_rts');
    }
};
