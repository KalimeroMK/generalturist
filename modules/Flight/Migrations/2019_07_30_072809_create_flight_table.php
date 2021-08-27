<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use Modules\Flight\Models\Airline;
    use Modules\Flight\Models\Airport;
    use Modules\Flight\Models\BookingPassengers;
    use Modules\Flight\Models\Flight;
    use Modules\Flight\Models\FlightSeat;
    use Modules\Flight\Models\FlightTerm;
    use Modules\Flight\Models\SeatType;

    class CreateFlightTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            $this->down();
            Schema::create(FlightTerm::getTableName(), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('term_id')->nullable();
                $table->integer('target_id')->nullable();
                $table->bigInteger('create_user')->nullable();
                $table->bigInteger('update_user')->nullable();

                $table->softDeletes();
                $table->timestamps();
            });
            Schema::create(Airport::getTableName(), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('code')->unique();
                $table->string('address')->nullable();
                $table->integer('location_id')->nullable();
                $table->text('description')->nullable();
                $table->string('map_lat', 20)->nullable();
                $table->string('map_lng', 20)->nullable();
                $table->integer('map_zoom')->nullable();
                $table->bigInteger('create_user')->nullable();
                $table->bigInteger('update_user')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            Schema::create(Airline::getTableName(), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->integer('image_id')->nullable();
                $table->bigInteger('create_user')->nullable();
                $table->bigInteger('update_user')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            Schema::create(SeatType::getTableName(), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('code')->unique();
                $table->string('name')->nullable();
                $table->bigInteger('create_user')->nullable();
                $table->bigInteger('update_user')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });

            Schema::create(Flight::getTableName(), function (Blueprint $blueprint) {
                $blueprint->engine = 'InnoDB';
                $blueprint->bigIncrements('id');
                $blueprint->string('title')->nullable();
                $blueprint->string('code')->nullable();
                $blueprint->decimal('review_score',2,1)->nullable();
                $blueprint->dateTime('departure_time')->nullable();
                $blueprint->dateTime('arrival_time')->nullable();
                $blueprint->float('duration')->nullable();
                $blueprint->decimal('min_price', 12, 2)->nullable();
                $blueprint->unsignedBigInteger('airport_to')->nullable();
                $blueprint->unsignedBigInteger('airport_from')->nullable();
                $blueprint->unsignedBigInteger('airline_id')->nullable();
                $blueprint->string('status', 50)->nullable();

                $blueprint->foreign('airport_to')->references('id')->on(Airport::getTableName());
                $blueprint->foreign('airport_from')->references('id')->on(Airport::getTableName());
                $blueprint->foreign('airline_id')->references('id')->on(Airline::getTableName());

                $blueprint->bigInteger('create_user')->nullable();
                $blueprint->bigInteger('update_user')->nullable();
                $blueprint->timestamps();
                $blueprint->softDeletes();
            });
            Schema::create(FlightSeat::getTableName(), function (Blueprint $blueprint) {
                $blueprint->engine = 'InnoDB';
                $blueprint->bigIncrements('id');
                $blueprint->decimal('price', 12, 2)->nullable();
                $blueprint->integer('max_passengers')->nullable();
                $blueprint->unsignedBigInteger('flight_id')->nullable();
                $blueprint->string('seat_type')->nullable();
                $blueprint->string('person')->nullable();
                $blueprint->integer('baggage_check_in')->nullable();
                $blueprint->integer('baggage_cabin')->nullable();

                $blueprint->foreign('flight_id')->references('id')->on(Flight::getTableName());
                $blueprint->foreign('seat_type')->references('code')->on(SeatType::getTableName());

                $blueprint->bigInteger('create_user')->nullable();
                $blueprint->bigInteger('update_user')->nullable();
                $blueprint->timestamps();
                $blueprint->softDeletes();
            });
            Schema::create(BookingPassengers::getTableName(), function (Blueprint $blueprint) {
                $blueprint->engine = 'InnoDB';

                $blueprint->bigIncrements('id');
                $blueprint->unsignedBigInteger('flight_id')->nullable();
                $blueprint->unsignedBigInteger('flight_seat_id')->nullable();
                $blueprint->unsignedBigInteger('booking_id')->nullable();
                $blueprint->string('seat_type')->nullable();
                $blueprint->string('email')->nullable();
                $blueprint->string('first_name')->nullable();
                $blueprint->string('last_name')->nullable();
                $blueprint->string('phone')->nullable();
                $blueprint->dateTime('dob')->nullable();
                $blueprint->decimal('price', 12, 2)->nullable();
                $blueprint->string('id_card')->nullable();

                $blueprint->foreign('flight_id')->references('id')->on(Flight::getTableName());
                $blueprint->foreign('flight_seat_id')->references('id')->on(FlightSeat::getTableName());
                $blueprint->foreign('seat_type')->references('code')->on(SeatType::getTableName());
                $blueprint->bigInteger('create_user')->nullable();
                $blueprint->bigInteger('update_user')->nullable();
                $blueprint->timestamps();
                $blueprint->softDeletes();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists(FlightTerm::getTableName());
            Schema::dropIfExists(Airport::getTableName());
            Schema::dropIfExists(Airline::getTableName());
            Schema::dropIfExists(SeatType::getTableName());
            Schema::dropIfExists(Flight::getTableName());
            Schema::dropIfExists(FlightSeat::getTableName());
            Schema::dropIfExists(BookingPassengers::getTableName());
        }
    }
