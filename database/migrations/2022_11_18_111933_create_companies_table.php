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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('fiscal_code')->nullable();
            $table->string('vat')->nullable();
            $table->string('pec')->nullable();
            $table->string('code_acronym')->nullable();
            $table->string('code_accounting')->nullable();
            $table->text('category')->nullable();
            $table->text('note')->nullable();

            $table->string('invoice_code')->nullable();

            $table->integer('default_bank')->nullable();
            $table->decimal('default_tax_rate', 10, 2)->nullable();
            $table->string('default_currency')->default('€');
            $table->string('default_payment')->nullable();

            $table->text('page_header')->nullable();
            $table->text('page_footer')->nullable();
            $table->text('page_warehouse_terms')->nullable();
            $table->text('page_warehouse_info')->nullable();

            $table->boolean('is_activated')->nullable()->default(true);

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
        Schema::dropIfExists('companies');
    }
};
