<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrom190To200 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bravo_attrs', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_attrs', 'hide_in_filter_search')) {
                $table->tinyInteger('hide_in_filter_search')->nullable();
            }
        });
        Schema::table('core_pages', function (Blueprint $table) {
            if (!Schema::hasColumn('core_pages', 'header_style')) {
                $table->string('header_style',255)->nullable();
            }
            if (!Schema::hasColumn('core_pages', 'custom_logo')) {
                $table->integer('custom_logo')->nullable();
            }
        });

        Schema::table('bravo_events', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_events', 'end_time')) {
                $table->string('end_time',255)->nullable();
            }
            if (!Schema::hasColumn('bravo_events', 'duration_unit')) {
                $table->string('duration_unit',255)->nullable();
            }
        });

        if (!Schema::hasTable("bravo_booking_time_slots")) {
            Schema::create("bravo_booking_time_slots", function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->integer('booking_id')->nullable();
                $table->bigInteger('object_id')->nullable();
                $table->string('object_model', 40)->nullable();
                $table->time('start_time')->nullable();
                $table->time('end_time')->nullable();
                $table->float('duration',255)->nullable();
                $table->string('duration_unit',255)->nullable();

                $table->integer('create_user')->nullable();
                $table->integer('update_user')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('bravo_hotel_rooms', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_hotel_rooms', 'min_day_stays')) {
                $table->integer('min_day_stays')->nullable();
            }
        });

        Schema::table('bravo_attrs', function (Blueprint $table) {
            if (!Schema::hasColumn('bravo_attrs', 'position')) {
                $table->smallInteger('position')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
