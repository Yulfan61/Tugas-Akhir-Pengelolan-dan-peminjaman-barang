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
    Schema::table('borrowings', function (Blueprint $table) {
        if (!Schema::hasColumn('borrowings', 'returned_by')) {
            $table->unsignedBigInteger('returned_by')->nullable()->after('status');
            $table->foreign('returned_by')->references('id')->on('users')->nullOnDelete();
        }
    });
}

public function down()
{
    Schema::table('borrowings', function (Blueprint $table) {
        $table->dropForeign(['returned_by']);
        $table->dropColumn('returned_by');
    });
}


};
