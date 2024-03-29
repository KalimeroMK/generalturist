<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('bravo_tours', function (Blueprint $table) {
            $table->tinyInteger('default_state')->default(1)->nullable();
            $table->tinyInteger('enable_fixed_date')->default(false)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->dateTime('last_booking_date')->nullable();
        });
        Schema::create('bravo_tour_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('target_id')->nullable();

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->text('person_types')->nullable();
            $table->tinyInteger('max_guests')->nullable();
            $table->tinyInteger('active')->default(0)->nullable();
            $table->text('note_to_customer')->nullable();
            $table->text('note_to_admin')->nullable();
            $table->tinyInteger('is_instant')->default(0)->nullable();

            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
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
        Schema::dropIfExists('bravo_tour_dates');
    }
};
