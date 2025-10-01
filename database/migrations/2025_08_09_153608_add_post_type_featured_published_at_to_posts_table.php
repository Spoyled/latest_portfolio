<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'post_type')) {
                $table->string('post_type', 50)->default('job_offer');
                $table->index('post_type');
            }
            if (!Schema::hasColumn('posts', 'featured')) {
                $table->boolean('featured')->default(false);
                $table->index('featured');
            }
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable();
                $table->index('published_at');
            }
        });
    }
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'post_type')) $table->dropColumn('post_type');
            if (Schema::hasColumn('posts', 'featured')) $table->dropColumn('featured');
            if (Schema::hasColumn('posts', 'published_at')) $table->dropColumn('published_at');
        });
    }
};
