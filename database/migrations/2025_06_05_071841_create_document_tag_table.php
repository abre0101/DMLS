<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
{
    Schema::create('tags', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->timestamps();
    });

    Schema::create('document_tag', function (Blueprint $table) {
        $table->foreignId('document_id')->constrained()->onDelete('cascade');
        $table->foreignId('tag_id')->constrained()->onDelete('cascade');
        $table->primary(['document_id', 'tag_id']);
    });
}

    

    public function down(): void
    {
        Schema::dropIfExists('document_tag');
    }
};
