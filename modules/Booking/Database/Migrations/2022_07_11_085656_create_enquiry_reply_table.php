<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bravo_enquiry_replies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();

            $table->bigInteger('user_id')->nullable();
            $table->text('content')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();

            $table->index(['parent_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('bravo_enquiry_replies');
        Schema::dropIfExists('enquiry_reply');
    }
};
