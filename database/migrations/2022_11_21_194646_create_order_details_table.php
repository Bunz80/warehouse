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
            $table->text('brand')->nullable();
            $table->text('code')->nullable();
            $table->text('description')->nullable();

            $table->string('currency')->nullable()->default('€');
            $table->string('unit')->nullable();                         //mt, pz, lt, kg, ecc
            $table->decimal('tax', 12, 2)->nullable();                  //22.00 %
            $table->decimal('quantity', 5, 2)->nullable()->default(1);  //2
            $table->decimal('price_unit', 12, 2)->nullable();           //10
            $table->decimal('price_total', 12, 2)->nullable();        //20
            $table->decimal('price_tax', 12, 2)->nullable();            //4,4
            $table->decimal('total', 12, 2)->nullable();          //24,40
            
            $table->string('discount_currency')->nullable()->default('€');
            $table->decimal('discount_price', 12, 2)->nullable();
            

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
