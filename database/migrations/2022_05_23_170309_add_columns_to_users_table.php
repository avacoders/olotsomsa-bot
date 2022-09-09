<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_bot');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('language_code')->default('uz');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_bot');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('username');
            $table->dropColumn('phone_number');
            $table->dropColumn('language_code');
        });
    }
};
