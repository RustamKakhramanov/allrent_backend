<?php

use App\Enums\CurrencyEnum;
use App\Enums\PriceTypeEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default(PriceTypeEnum::PerHour());
            $table->string('currency')->default(CurrencyEnum::KZT());
            $table->unsignedDecimal('value');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->morphs('has_price');
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
        Schema::dropIfExists('prices');
    }
};
