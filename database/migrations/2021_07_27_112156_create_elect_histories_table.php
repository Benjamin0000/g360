<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elect_histories', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id');
            $table->string('name')->nullable();
            $table->string('address', 350)->nullable();
            $table->string('product_id');
            $table->decimal('amount', 65, 2);
            $table->decimal('fee', 65, 2);
            $table->string('pin_code');
            $table->string('operator_name');
            $table->string('customer_reference');
            $table->string('reference');
            $table->string('meter_no');
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
        Schema::dropIfExists('elect_histories');
    }
}
