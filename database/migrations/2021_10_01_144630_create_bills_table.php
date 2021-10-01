<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('billing_number', 50);
            $table->bigInteger('user_id_created');
            $table->bigInteger('user_id_updated')->nullable();
            $table->tinyInteger('billing_status')->comment('0 cancel; 1 new; 2 done');
            $table->double('billing_total');
            $table->bigInteger('registration_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
