<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tradings', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->bigInteger('gnumber'); 
            $table->string('name');
            $table->decimal('amount', 65, 2);
            $table->decimal('returned', 65, 2)->default(0);
            $table->decimal('amt_return', 65, 2); //amount returning
            $table->float('interest');
            $table->decimal('interest_amt', 65, 2);
            $table->boolean('interest_returned')->default(0);
            $table->integer('exp_days');
            $table->boolean('status')->default(0);
            $table->string('ref_percent')->nullable();
            $table->timestamp('last_added');
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
        Schema::dropIfExists('tradings');
    }
}
