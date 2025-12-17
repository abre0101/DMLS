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
Schema::create('reports', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('status'); // approved, rejected, etc.
    $table->unsignedBigInteger('generated_by')->nullable();
    $table->foreignId('document_id')->constrained()->onDelete('cascade');

    $table->timestamps();

    $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
});



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
