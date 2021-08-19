<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptionsProducts extends Migration
{
    private $tableName = 'options_products';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id();
            $table->foreignId('option_id')->references('id')->on('options')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('value');
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
        Schema::dropIfExists($this->tableName);
    }
}
