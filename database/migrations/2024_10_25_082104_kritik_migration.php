<?php

use App\Models\Rt;
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
        Schema::create('kritiks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Rt::class)->nullable();
            $table->foreignIdFor(Rw::class)->nullable();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->text('text');
            $table->enum('status', ['yet', 'read', 'done'])->default('yet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kritiks');
    }
};
