<?php

use App\Models\RekeningRw;
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
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rw::class)->nullable();
            $table->foreignIdFor(Rt::class)->nullable();
            $table->enum('variance', ['debit', 'credit']);
            $table->float('value');
            $table->float('before');
            $table->float('after');
            $table->string('notes');
            $table->string('image')->nullable();
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
