<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'location')) {
                if (Schema::hasColumn('posts', 'salary')) {
                    $table->string('location')->nullable()->after('salary');
                } else {
                    $table->string('location')->nullable();
                }
            }
        });
    }

    public function down(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'location')) {
                $table->dropColumn('location');
            }
        });
    }
};
