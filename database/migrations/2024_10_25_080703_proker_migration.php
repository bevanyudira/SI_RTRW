<?php

use App\Models\Proker;
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
        Schema::create('prokers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->time('time');
            $table->date('date');
            $table->string('location');
            $table->string('image')->nullable();
            $table->foreignIdFor(Rw::class)->nullable();
            $table->foreignIdFor(Rt::class)->nullable();
            $table->enum('status', ['progress', 'done'])->default('progress');
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
