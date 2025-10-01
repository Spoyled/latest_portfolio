<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            // employer_id -> employers.id (nullable, safe if employers exists)
            if (!Schema::hasColumn('posts', 'employer_id')) {
                $table->foreignId('employer_id')->nullable()
                      ->constrained('employers')->nullOnDelete();
            }
            // description text (keep your existing title/body as-is)
            if (!Schema::hasColumn('posts', 'description')) {
                // place it after title if title exists, otherwise just add it
                if (Schema::hasColumn('posts', 'title')) {
                    $table->text('description')->nullable()->after('title');
                } else {
                    $table->text('description')->nullable();
                }
            }
        });
    }

    public function down(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'employer_id')) {
                $table->dropConstrainedForeignId('employer_id');
            }
            if (Schema::hasColumn('posts', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
