<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up()
    {
        // Create categories table
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Unique category name
            $table->timestamps(); // Created at and updated at timestamps
        });

        // Create documents table
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Searchable field
            $table->text('description')->nullable(); // Searchable description
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Document status
            $table->text('metadata')->nullable(); // Metadata for the document
            $table->string('author')->default('Unknown'); // Document author
            $table->string('department');
            $table->string('file_path');
            $table->string('file_type'); 
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            $table->unsignedBigInteger('user_id');
            $table->integer('version')->default(1); 
            $table->string('watermark')->nullable(); 
            $table->timestamps();
            $table->softDeletes(); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

     
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id'); 
            $table->text('content')->nullable(); 
            $table->timestamps(); 

         
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('versions');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('categories');
    }
};