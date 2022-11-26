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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->text('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();

            $table->string('code')->nullable();
            $table->decimal('tax', 12, 2)->nullable();
            $table->string('unit')->nullable();
            $table->string('currency')->nullable()->default('€');
            $table->decimal('price', 12, 2)->nullable();

            $table->text('category', 100)->nullable();

            $table->unsignedBigInteger('supplier_id')->unsigned()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

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
        Schema::dropIfExists('products');
    }
};
