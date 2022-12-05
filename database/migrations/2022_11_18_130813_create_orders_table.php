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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedBigInteger('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            // Info Order
            $table->year('year')->nullable();
            $table->integer('number')->nullable();
            $table->date('order_at')->nullable();
            $table->date('close_at')->nullable();
            $table->string('status')->default('new');
            // Discount
            // $table->string('discount_currency')->nullable();
            // $table->decimal('discount_price', 12, 2)->nullable();
            // Valuta e prezzo totale
            $table->string('currency')->default('€');
            $table->decimal('total_taxes', 12, 2)->nullable();
            $table->decimal('total_prices', 12, 2)->nullable();
            $table->decimal('total_order', 12, 2)->nullable();

            // Delivery -> address - contact - method - notes
            $table->unsignedBigInteger('address_id')->nullable()->unsigned();
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->text('address')->nullable();

            $table->unsignedBigInteger('contact_id')->nullable()->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->text('contact')->nullable();


            $table->string('delivery_method')->nullable();
            $table->text('delivery_note')->nullable();

            // Trasporto
            $table->string('trasport_method')->nullable();
            $table->text('trasport_note')->nullable();
            // Pagamento
            $table->string('payment_method')->nullable();
            $table->text('payment_note')->nullable();

            $table->text('notes')->nullable();
            $table->text('report')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
