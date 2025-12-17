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
        // Create approval hierarchies table
        Schema::create('approval_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->string('document_type'); // e.g., 'contract', 'invoice'
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->integer('level');
            $table->foreignId('next_level_id')->nullable()->constrained('roles')->onDelete('set null');
            $table->timestamps();
        });

        // Create approval requests table
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained()->onDelete('cascade');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('escalated_to_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ✅ Added user_id column
            $table->integer('level')->default(1);
            $table->enum('status', ['pending', 'approved', 'rejected', 'escalated'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('signature_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_hierarchies');
    }
};