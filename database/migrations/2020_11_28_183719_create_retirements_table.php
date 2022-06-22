<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_proceeding_id');
            $table->foreign('type_proceeding_id')->references('id')->on('types_proceedings');
            $table->unsignedBigInteger('office_id');
            $table->foreign('office_id')->references('id')->on('offices');
            $table->unsignedBigInteger('interested_id');
            $table->foreign('interested_id')->references('id')->on('persons');
            $table->unsignedBigInteger('spouse_id')->nullable();
            $table->foreign('spouse_id')->references('id')->on('persons');
            $table->unsignedBigInteger('retirement_id');
            $table->foreign('retirement_id')->references('id')->on('persons');
            $table->unsignedBigInteger('turn_id');
            $table->foreign('turn_id')->references('id')->on('turns');
            $table->date('generate_date');
            $table->string('tome_book');
            $table->integer('number_copies');
            $table->boolean('status');
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
        Schema::dropIfExists('retirements');
    }
}
