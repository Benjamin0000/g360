<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lmps', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('name');
            $table->string('user_id', 30);
            $table->bigInteger('gnumber');
            $table->integer('rank_id');
            $table->decimal('amount', 65, 2);
            $table->integer('times')->default(0); #number of times received
            $table->integer('total_times'); #total times to receive
            $table->boolean('status')->default(0);
            $table->timestamp('last_payed')->nullable();
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
        Schema::dropIfExists('lmps');
    }
}
