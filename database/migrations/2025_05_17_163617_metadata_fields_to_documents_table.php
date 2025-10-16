<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
       
      
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->timestamp('rejected_at')->nullable()->after('approved_at');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');

            // Foreign keys (optional)
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('rejected_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            $table->dropColumn([
                'tags',
                'ocr_text',
                'author',
                'approved_at',
                'rejected_at',
                'approved_by',
                'rejected_by'
            ]);
        });
    }
};

