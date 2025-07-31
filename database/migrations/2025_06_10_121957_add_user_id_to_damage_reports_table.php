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
        Schema::table('damage_reports', function (Blueprint $table) {
            // Menambahkan kolom user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('item_id');

            // Menambahkan foreign key constraint (pastikan tabel users memiliki kolom id)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('damage_reports', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['user_id']);

            // Hapus kolom user_id
            $table->dropColumn('user_id');
        });
    }
};
