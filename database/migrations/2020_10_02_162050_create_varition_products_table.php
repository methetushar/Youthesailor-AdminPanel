<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaritionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('varition_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->nullable();
            $table->string('extra_image')->nullable();
            $table->string('color_id')->nullable();
            $table->string('size_id')->nullable();
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
        Schema::dropIfExists('varition_products');
    }
}
