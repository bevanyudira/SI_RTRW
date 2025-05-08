<?php

use App\Models\Iuran;
use App\Models\IuranRw;
use App\Models\Rt;
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
        Schema::create('detail_iurans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Iuran::class);
            $table->foreignIdFor(User::class);
            $table->enum('status', ['yet', 'pending', 'done', 'failed'])->default('yet');
            $table->bigInteger('bank');
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_iurans');
    }
};
