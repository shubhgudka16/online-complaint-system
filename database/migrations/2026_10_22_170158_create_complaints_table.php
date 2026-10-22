<?php

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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 20)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium')->index();
            $table->enum('status', ['Pending', 'In Progress', 'Resolved', 'Rejected'])->default('Pending')->index();
            $table->text('admin_remarks')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['department_id', 'status']);
            $table->index(['status', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
