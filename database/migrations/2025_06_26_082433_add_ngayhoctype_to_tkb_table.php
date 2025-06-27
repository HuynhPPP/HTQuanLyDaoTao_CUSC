<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNgayhoctypeToTkbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tkb', function (Blueprint $table) {
            $table->string('ngayHocType')->default('all')->after('NgayHoc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tkb', function (Blueprint $table) {
            $table->dropColumn('ngayHocType');
        });
    }
}
