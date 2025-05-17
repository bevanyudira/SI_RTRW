<?php

use App\Models\Warga;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Warga::class)->unique()->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('phone');
            $table->string('password');
            $table->integer('balance')->nullable();
            $table->char('pin', 4)->default('0000');
            $table->enum('role', ['Ketua_RT', 'Ketua_RW', 'Admin_RT', 'Admin_RW', 'Super_Admin', 'Warga']);
            $table->boolean('activated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
