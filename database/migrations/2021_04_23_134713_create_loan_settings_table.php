<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateLoanSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('min', 65, 2);
            $table->decimal('max', 65, 2);
            $table->decimal('interest', 65, 2);
            $table->decimal('f_interest', 65, 2);
            $table->integer('exp_months');
            $table->integer('grace_months');
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
        Schema::dropIfExists('loan_settings');
    }
}
