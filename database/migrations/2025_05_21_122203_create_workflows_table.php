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
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('document_id')->constrained()->onDelete('cascade'); // Workflow linked to a document
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('description')->nullable(); // Optional description
            $table->timestamps();
        });

        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('step_order'); // Step sequence

            // Replace approver_id with role
            $table->string('role'); // e.g. 'employee', 'manager', 'director'
$table->foreignId('approver_id')->constrained('users');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('comment')->nullable();
            $table->longText('signature')->nullable(); // Optional e-signature
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_steps');
        Schema::dropIfExists('workflows');
    }
};
