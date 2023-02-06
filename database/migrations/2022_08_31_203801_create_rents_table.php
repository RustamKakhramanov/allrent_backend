<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->morphs('rentable');
            $table->foreignId('specialist_profile_id')->nullable();
            $table->string('type')->nullable();
            $table->json('detail')->nullable();
            $table->unsignedDecimal('amount')->nullable();
            $table->string('currency')->default('KZT');
            $table->boolean('is_paid')->default(false);
            $table->timestamp('scheduled_at');
            $table->timestamp('scheduled_end_at');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
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
        Schema::dropIfExists('rents');
    }
};
