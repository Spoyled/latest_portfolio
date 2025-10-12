<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prosnap_posts', function (Blueprint $table) {
            // jei NĖRA employer_id – pridedam
            if (!Schema::hasColumn('prosnap_posts', 'employer_id')) {
                $table->foreignId('employer_id')
                      ->after('id')
                      ->constrained('employers')   // jei tavo lentelė vadinasi kitaip – pakeisk
                      ->cascadeOnDelete();
            }

            // jei NĖRA closed_at – pridedam
            if (!Schema::hasColumn('prosnap_posts', 'closed_at')) {
                $table->timestamp('closed_at')->nullable()->after('updated_at');
            }

            // Optional: jei naudoji softDeletes
            if (!Schema::hasColumn('prosnap_posts', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('prosnap_posts', function (Blueprint $table) {
            if (Schema::hasColumn('prosnap_posts', 'employer_id')) {
                $table->dropConstrainedForeignId('employer_id');
            }
            if (Schema::hasColumn('prosnap_posts', 'closed_at')) {
                $table->dropColumn('closed_at');
            }
            if (Schema::hasColumn('prosnap_posts', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
