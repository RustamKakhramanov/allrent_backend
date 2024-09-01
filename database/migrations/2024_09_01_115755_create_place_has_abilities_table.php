<?php

use App\Models\Ability;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Location\Place;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_has_abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Place::class);
            $table->foreignIdFor(Ability::class);
            $table->unsignedInteger('sort')->nullable();
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
        Schema::dropIfExists('place_has_abilities');
    }
};
