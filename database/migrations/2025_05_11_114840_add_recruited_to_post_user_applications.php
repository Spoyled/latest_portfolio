<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('post_user_applications', function (Blueprint $table) {
            $table->boolean('recruited')->default(false);
        });
    }

    public function down()
    {
        Schema::table('post_user_applications', function (Blueprint $table) {
            $table->dropColumn('recruited');
        });
    }

};
