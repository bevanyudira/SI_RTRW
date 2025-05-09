<?php

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
        Schema::create('rws', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bank');
            $table->integer('balance')->default(0);
            $table->timestamps();
        });
        Schema::create('rts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rw::class);
            $table->string('name');
            $table->string('bank');
            $table->integer('balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rws');
        Schema::dropIfExists('rts');
    }
};
