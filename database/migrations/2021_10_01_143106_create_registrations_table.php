<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id');
            $table->bigInteger('paramedic_id');
            $table->bigInteger('user_id_created');
            $table->bigInteger('user_id_updated')->nullable();
            $table->string('queue_number', 30);
            $table->tinyInteger('queue_status')->comment('0 cancel; 1 new; 2 process; 3 done')->default(1);
            $table->string('registration_number', 50);
            $table->tinyInteger('registration_status')->comment('0 cancel; 1 new; 2 process; 3 done')->default(1);
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
        Schema::dropIfExists('registrations');
    }
}
