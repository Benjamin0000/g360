<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreatePContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_contracts', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('partner_id', 30);
            $table->decimal('amount', 65, 2);
            $table->decimal('total_return', 65, 2);
            $table->decimal('returned', 65, 2)->default(0);
            $table->integer('months')->nullable();
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
        Schema::dropIfExists('p_contracts');
    }
}
