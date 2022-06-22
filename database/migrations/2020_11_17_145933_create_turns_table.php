<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->unsignedBigInteger('creator_id');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->unsignedBigInteger('allocator_id')->nullable();
            $table->foreign('allocator_id')->references('id')->on('users');
            $table->unsignedBigInteger('delegation_id');
            $table->foreign('delegation_id')->references('id')->on('delegations');
            $table->unsignedBigInteger('person_id')->nullable();
            $table->foreign('person_id')->references('id')->on('persons');
            $table->unsignedBigInteger('type_procedure_id')->nullable();
            $table->foreign('type_procedure_id')->references('id')->on('types_procedures');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turns');
    }
}
