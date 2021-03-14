<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('name');
            $table->string('user_id', 30);
            $table->bigInteger('gnumber');
            $table->integer('rank_id');
            $table->decimal('loan_amount', 64, 2);
            $table->integer('loan_month');
            $table->decimal('lmp_amount', 64, 2);
            $table->integer('lmp_month');
            $table->boolean('status')->default(0);
            $table->string('selected', 5)->nullable();
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
        Schema::dropIfExists('rewards');
    }
}
