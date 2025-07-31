<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->string('return_photo')->nullable()->after('quantity');
        });
    }

    public function down()
    {
        Schema::table('borrowing_items', function (Blueprint $table) {
            $table->dropColumn('return_photo');
        });
    }
};
