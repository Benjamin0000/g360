<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCircleBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circle_bonuses', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->integer('point')->default(0);
            $table->integer('times')->default(1);
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
        Schema::dropIfExists('circle_bonuses');
    }
}
