<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_records', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('visit_record_type')->comment('1 S (Subject); 2 O (Objective); 3 A (Asessment); 4 P (Plan)');
            $table->text('note');
            $table->bigInteger('user_id_created');
            $table->bigInteger('user_id_updated')->nullable();
            $table->bigInteger('user_id_deleted')->nullable();
            $table->bigInteger('diagnosis_id');
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
        Schema::dropIfExists('visit_records');
    }
}
