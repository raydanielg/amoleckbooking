<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->default('Physiotherapy session');
            $table->string('status', 20)->default('pending'); // pending, in_progress, successful, cancelled
            $table->timestamp('scheduled_at');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['patient_id', 'scheduled_at']);
            $table->index(['doctor_id', 'scheduled_at']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
