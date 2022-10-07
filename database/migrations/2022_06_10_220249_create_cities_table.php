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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained();
            $table->string('name');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->decimal('ne_latitude')->nullable();
            $table->decimal('ne_longitude')->nullable();
            $table->decimal('sw_latitude')->nullable();
            $table->decimal('sw_longitude')->nullable();
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
        Schema::dropIfExists('cities');
    }
};
