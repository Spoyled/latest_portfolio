<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('post_user_applications')) {
            return; // table already imported from legacy DB
        }
        Schema::create('post_user_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('post_id');
            $table->string('cv_path')->nullable();
            $table->timestamps();

            // Add FKs only if referenced tables exist in your schema
            // (Skip if you prefer—legacy DB already has data)
            // $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            // $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete();
        });
    }
    public function down(): void {
        if (Schema::hasTable('post_user_applications')) {
            Schema::drop('post_user_applications');
        }
    }
};
