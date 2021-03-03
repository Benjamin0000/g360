<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEpinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('epins', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->integer('pkg_id');
            $table->string('code', 30);
            $table->string('user_id', 30);
            $table->bigInteger('gnumber');
            $table->string('used_by', 30)->nullable();
            $table->timestamp('used_date')->nullable();
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('epins');
    }
}
