<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAssociatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_associates', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->integer('grace')->default(0);
            $table->timestamp('last_grace')->nullable();
            $table->boolean('status')->default(0); // 1 if grace expired || 2 if won || 3 if claimed // 4 activate super associate
            $table->boolean('balance_leg')->default(0); // leg not balanced show become 1
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
        Schema::dropIfExists('super_associates');
    }
}
