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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->text('name');
            $table->text('brand');
            $table->text('code');
            $table->text('description')->nullable();

            $table->decimal('tax', 12, 2)->nullable();
            $table->decimal('quantity', 5, 2)->nullable()->default(1);
            $table->string('unit')->nullable();
            $table->string('currency')->nullable()->default('€');

            $table->string('discount_currency')->nullable()->default('€');
            $table->decimal('discount_price', 12, 2)->nullable();

            $table->decimal('price', 12, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
};
