<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('post_user_applications')) return;
        Schema::table('post_user_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('post_user_applications', 'recruited')) {
                $table->boolean('recruited')->default(false);
            }
        });
    }
    public function down(): void {
        if (!Schema::hasTable('post_user_applications')) return;
        Schema::table('post_user_applications', function (Blueprint $table) {
            if (Schema::hasColumn('post_user_applications', 'recruited')) {
                $table->dropColumn('recruited');
            }
        });
    }
};
