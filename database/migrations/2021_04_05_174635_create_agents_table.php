<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->string('id', 30)->primary();
            $table->string('user_id', 30);
            $table->string('ref_by', 30)->nullable();
            $table->decimal('fbronz_deca', 65, 2)->default(0); //finance
            $table->decimal('gsilver_deca', 65, 2)->default(0); //general market
            $table->decimal('rgold_deca', 65, 2)->default(0); //referral
            $table->decimal('pos_deca', 65, 2)->default(0); // pos
            $table->string('sg_ref_total')->default(0); // super agent ref total
            $table->string('pin')->nullable();
            $table->boolean('auth')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('type')->default(0);
            $table->integer('state_id');
            $table->integer('city_id');
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
        Schema::dropIfExists('agents');
    }
}
