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
    {Schema::create('document_versions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('document_id')->constrained()->onDelete('cascade');
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('author')->nullable();
    $table->string('department');
    $table->string('file_path');
    $table->string('file_type');
    $table->json('tags')->nullable();
    $table->integer('version_number');
    $table->timestamps();
});

        
    }

    public function down(): void
    {
        //
    }
};