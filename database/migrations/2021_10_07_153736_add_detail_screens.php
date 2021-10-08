<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailScreens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->string('url', 100)->nullable()->change();
            $table->string('icon')->nullable();
            $table->tinyInteger('is_menu')->default(1)->comment('1 yes 0 no');
            $table->tinyInteger('is_sub_menu')->default(0)->comment('1 yes 0 no');
            $table->bigInteger('screen_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('screens', function (Blueprint $table) {
            $table->string('url', 100)->change();
            $table->dropColumn('icon');
            $table->dropColumn('is_menu');
            $table->dropColumn('is_sub_menu');
            $table->dropColumn('screen_id');
        });
    }
}
