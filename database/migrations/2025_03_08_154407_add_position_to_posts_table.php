<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'position')) {
                if (Schema::hasColumn('posts', 'location')) {
                    $table->string('position')->nullable()->after('location');
                } else {
                    $table->string('position')->nullable();
                }
            }
        });
    }

    public function down(): void {
        if (!Schema::hasTable('posts')) return;

        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'position')) {
                $table->dropColumn('position');
            }
        });
    }
};
