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
    Schema::table('letter_templates', function (Blueprint $table) {
        $table->unsignedBigInteger('department_id')->nullable()->after('id');

        // Add foreign key if departments table exists
        $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
    });
}

    public function down(): void
    {
        Schema::table('letter_templates', function (Blueprint $table) {
            //
        });
    }
};
