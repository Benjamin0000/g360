<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGsClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gs_clubs', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->decimal('wbal', 65, 2)->default(0);
            $table->decimal('gbal', 65, 2)->default(0);
            $table->boolean('g')->default(0);
            $table->timestamp('lastg')->nullable();
            $table->timestamp('lastr')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('r_count')->default(0);
            $table->bigInteger('circle')->default(0);
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
        Schema::dropIfExists('gs_clubs');
    }
}
