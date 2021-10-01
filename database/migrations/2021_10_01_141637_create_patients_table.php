<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('medical_number', 50);
            $table->string('full_name');
            $table->string('place_of_birth', 50);
            $table->date('date_of_birth');
            $table->string('address');
            $table->string('phone_number', 12);
            $table->string('email', 100);
            $table->tinyInteger('identity_type')->comment('1 ID Card; 2 Driver License; 3 Passport');
            $table->string('identity_number', 16);
            $table->bigInteger('user_id_created');
            $table->bigInteger('user_id_updated')->nullable();
            $table->bigInteger('user_id_deleted')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
