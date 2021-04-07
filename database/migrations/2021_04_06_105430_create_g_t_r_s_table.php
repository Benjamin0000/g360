<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GTR;
class CreateGTRSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('g_t_r_s', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 65,2);
            $table->decimal('pay_back', 65,2);
            $table->integer('r_count'); // total receivers
            $table->integer('g_hours'); // hours before giving
            $table->integer('r_hours');// days before receiving
            $table->integer('total_ref'); // total referrals
            $table->integer('level');
            $table->timestamps();
        });
        $amounts = [1500, 2500, 7500, 30500, 83000, 215000, 460000, 580000];
        $paybacks = [2500, 7500, 30500, 83000, 215000, 460000, 580000, 19750];
        $rcounts = [7, 7, 7, 6, 5, 4, 3, 2];
        $ghours = 24;
        $rhours = [7, 15, 15, 15, 15, 15, 15, 15];
        $totalRefs = [1, 3, 6, 12, 24, 50, 60, 70];
        for($i = 0; $i < count($amounts); $i++){
            GTR::create([
                'amount'=>$amounts[$i],
                'pay_back'=>$paybacks[$i],
                'r_count'=>$rcounts[$i],
                'g_hours'=>$ghours,
                'r_hours'=>$rhours[$i],
                'total_ref'=>$totalRefs[$i],
                'level'=>$i+1
            ]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('g_t_r_s');
    }
}
