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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->morphs('addressable');
            $table->string('collection_name');  //residenza, sede legale, ecc..
            $table->string('name');
            $table->string('address');
            $table->string('street_number')->nullable();
            $table->string('zip')->nullable();
            $table->string('city');
            $table->string('province')->nullable();
            $table->string('state')->nullable()->default('Italia');

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
        Schema::dropIfExists('addresses');
    }
};
