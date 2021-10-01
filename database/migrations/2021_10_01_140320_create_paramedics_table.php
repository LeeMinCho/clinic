<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParamedicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paramedics', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->tinyInteger('paramedic_type')->comment('1 doctor; 2 nurse; 3 pharmacist');
            $table->string('registration_number', 100)->nullable();
            $table->string('phone_number', 12);
            $table->string('address');
            $table->tinyInteger('identity_type')->comment('1 ID Card; 2 Driver License; 3 Passport');
            $table->string('identity_number', 16);
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
        Schema::dropIfExists('paramedics');
    }
}
