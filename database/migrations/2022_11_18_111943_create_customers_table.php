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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname')->nullable();
            $table->string('logo')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->date('date_birth')->nullable();
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

            $table->boolean('is_activated')->nullable()->default(true);
            $table->boolean('is_person')->nullable()->default(false);

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
        Schema::dropIfExists('customers');
    }
};
