<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddGraceInterestToRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ranks', function (Blueprint $table) {
            $table->decimal('loan_interest', 65, 2)->default(0);
            $table->decimal('loan_g_interest', 65, 2)->default(0);
            $table->integer('loan_g_exp_m')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ranks', function (Blueprint $table) {
            $table->dropColumn(['loan_interest', 'loan_g_interest', 'loan_g_exp_m']);
        });
    }
}
