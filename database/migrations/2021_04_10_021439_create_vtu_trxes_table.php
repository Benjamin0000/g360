<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateVtuTrxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vtu_trxes', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->decimal('amount', 65, 2);
            $table->string('type');
            $table->string('service');
            $table->string('description', 1000)->nullable();
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
        Schema::dropIfExists('vtu_trxes');
    }
}
