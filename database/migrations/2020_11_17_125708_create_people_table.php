<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('address');
            $table->string('location');
            $table->string('postal_code');
            $table->string('phone');
            $table->string('cell_phone');
            $table->string('another_phone');
            $table->string('email');
            $table->integer('gender');
            $table->integer('document_number');
            $table->unsignedBigInteger('document_type_id');
            $table->foreign('document_type_id')->references('id')->on('types_documents');
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
        Schema::dropIfExists('persons');
    }
}
