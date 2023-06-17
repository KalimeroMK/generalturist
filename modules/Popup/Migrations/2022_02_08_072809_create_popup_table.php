<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Popup\Models\Popup;
use Modules\Popup\Models\PopupTranslation;

class CreatePopupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Popup::getTableName(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('status', 50)->nullable()->default('draft');

            $table->text('include_url')->nullable();
            $table->text('exclude_url')->nullable();

            $table->string('schedule_type')->nullable()->default('day');
            $table->string('schedule_amount')->nullable()->default(0);

            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();

            $table->timestamps();
        });

        Schema::create(PopupTranslation::getTableName(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();

            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();

            $table->integer('origin_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['origin_id', 'locale']);

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
        Schema::dropIfExists(Popup::getTableName());
        Schema::dropIfExists(PopupTranslation::getTableName());
    }
}
