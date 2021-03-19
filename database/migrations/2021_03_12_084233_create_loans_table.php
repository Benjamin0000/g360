<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('garant', 30)->nullable(); #garantor id one
            $table->string('user_id', 30);
            $table->bigInteger('gnumber');
            $table->decimal('amount', 64, 2);
            $table->decimal('returned', 64, 2)->default(0);
            $table->decimal('total_return', 64, 2); #amount+(amount*interest/100)
            $table->decimal('garant_amt', 64, 2)->nullable();
            $table->integer('interest')->default(0);
            $table->integer('exp_months'); // expiry months
            $table->integer('grace_months'); // grace expiry months
            $table->boolean('status')->default(0);
            $table->boolean('g_approve')->default(0);
            $table->boolean('defaulted')->default(0);
            $table->text('extra')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamp('grace_date')->nullable();
            $table->timestamp('defaulted_at')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
