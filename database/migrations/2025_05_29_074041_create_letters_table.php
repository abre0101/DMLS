<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLettersTable extends Migration
{
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // for replies
            $table->text('content');
            $table->timestamps();

            // Foreign keys
            $table->foreign('template_id')->references('id')->on('letter_templates')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('letters')->onDelete('cascade'); // reply relationship
        });
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
}
