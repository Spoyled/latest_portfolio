<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('post_user_applications', function (Blueprint $table) {
            $table->dropForeign('post_user_applications_post_id_foreign');
        });

        DB::statement('
            ALTER TABLE `post_user_applications`
            ADD CONSTRAINT `post_user_applications_post_id_foreign`
            FOREIGN KEY (`post_id`) REFERENCES `prosnap_posts`.`posts`(`id`)
            ON DELETE CASCADE
        ');

        Schema::table('post_user_applications', function (Blueprint $table) {
            $table->unique(['post_id','user_id'], 'pua_post_user_unique');
        });
    }

    public function down(): void
    {
        Schema::table('post_user_applications', function (Blueprint $table) {
            $table->dropUnique('pua_post_user_unique');
            $table->dropForeign('post_user_applications_post_id_foreign');
        });

        DB::statement('
            ALTER TABLE `post_user_applications`
            ADD CONSTRAINT `post_user_applications_post_id_foreign`
            FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`)
            ON DELETE CASCADE
        ');
    }
};
