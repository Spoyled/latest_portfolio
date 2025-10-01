<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'closed_at')) {
                if (Schema::hasColumn('posts', 'published_at')) {
                    $table->timestamp('closed_at')->nullable()->after('published_at');
                } else {
                    $table->timestamp('closed_at')->nullable();
                }
            }
        });
    }

    public function down(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'closed_at')) {
                $table->dropColumn('closed_at');
            }
        });
    }
};
