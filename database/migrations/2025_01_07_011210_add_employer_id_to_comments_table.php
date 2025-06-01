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
        Schema::table('comments', function (Blueprint $table) {
            // Make 'user_id' nullable in case the commenter is an employer
            $table->unsignedBigInteger('user_id')->nullable()->change();
            
            // Add an employer_id column to store employer’s ID if they comment
            $table->unsignedBigInteger('employer_id')->nullable()->after('user_id');
            
            // If you want a foreign key (optional), do something like:
            // $table->foreign('employer_id')->references('id')->on('employers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            //
        });
    }
};
