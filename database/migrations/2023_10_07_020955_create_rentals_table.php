<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('car_model_id');
            $table->dateTime('pickup');
            $table->dateTime('dropoff');
            $table->dateTime('return_date');
            $table->float('daily_price', 8, 2);
            $table->integer('initial_km');
            $table->integer('final_km');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('car_model_id')->references('id')->on('car_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}
