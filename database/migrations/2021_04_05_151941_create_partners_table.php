<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->bigInteger('gnumber'); 
            $table->decimal('credited', 65, 2)->default(0);
            $table->decimal('debited', 65, 2)->default(0);
            $table->decimal('balance', 65, 2)->default(0);
            $table->string('pin')->nullable();
            $table->boolean('auth')->default(0);
            $table->boolean('type')->default(0);
            $table->float('s_credit')->default(0); // sign up
            $table->float('f_credit')->default(0); // finance
            $table->float('e_credit')->default(0); // eshop
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
        Schema::dropIfExists('partners');
    }
}
