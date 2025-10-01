<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('comments')) return;
        Schema::table('comments', function (Blueprint $table) {
            if (!Schema::hasColumn('comments', 'employer_id')) {
                $table->unsignedBigInteger('employer_id')->nullable()->after('user_id'); // add FK later if needed
            }
        });
    }
    public function down(): void {
        if (!Schema::hasTable('comments')) return;
        Schema::table('comments', function (Blueprint $table) {
            if (Schema::hasColumn('comments', 'employer_id')) {
                $table->dropColumn('employer_id');
            }
        });
    }
};
