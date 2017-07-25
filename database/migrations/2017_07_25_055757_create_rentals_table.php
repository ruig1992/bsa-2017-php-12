<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->integer('car_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            $table->integer('rented_from')->unsigned()->index();
            $table->integer('returned_to')->unsigned()->index();

            $table->dateTime('rented_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('returned_at')->nullable();

            $table->decimal('price', 10, 2)->unsigned()->nullable();

            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->foreign('rented_from')
                ->references('id')
                ->on('locations')
                ->onDelete('CASCADE');

            $table->foreign('returned_to')
                ->references('id')
                ->on('locations')
                ->onDelete('CASCADE');
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
