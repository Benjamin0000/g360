<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradePkgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_pkgs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('amount');
            $table->float('interest');
            $table->integer('pv')->default(0);
            $table->integer('ref_pv')->default(0);
            $table->string('ref_percent')->nullable();
            $table->string('min_pkg');
            $table->integer('exp_days');
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
        Schema::dropIfExists('trade_pkgs');
    }
}
