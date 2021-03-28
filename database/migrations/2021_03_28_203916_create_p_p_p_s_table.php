<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePPPSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_p_p_s', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->integer('grace')->default(0);
            $table->integer('point')->default(0);
            $table->boolean('status')->default(0);
            $table->integer('grace')->default(0);
            $table->timestamp('graced_at')->nullable();
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
        Schema::dropIfExists('p_p_p_s');
    }
}
