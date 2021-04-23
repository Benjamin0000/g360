<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Rank;
class CreateRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranks', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name');
            // $table->string('prize_name');
            $table->decimal('prize', 65, 2);
            $table->decimal('loan', 65, 2);
            $table->decimal('fee', 65, 2)->default(0);
            $table->integer('minutes')->default(0);
            $table->integer('graced_minutes')->default(0);
            $table->integer('pv');
            $table->integer('loan_exp_m'); #expiry months
            $table->decimal('total_lmp', 65, 2);
            $table->decimal('carry_over', 65, 2)->default(0);
            $table->integer('lmp_months'); #total months to pay lmp
            $table->timestamps();
        });
        $names = [
            'super associate',
            'senior associate',
            'assistant manager',
            'manager',
            '1 star manager',
            '2 star manager',
            '3 star manager',
            '4 star manager',
            '5 star manager',
            'director'
        ];
        $pvs = [
            9000,
            30000,
            84000,
            250000,
            600000,
            1450000,
            3600000,
            8900000,
            19800000,
            50000000
        ];
        $prizes = [
            50000,
            200000,
            2000000,
            3500000,
            5000000,
            12000000,
            18000000,
            24000000,
            30000000,
            50000000
        ];
        $loans = [
            100000,
            500000,
            1000000,
            1500000,
            2000000,
            4000000,
            5000000,
            6000000,
            5000000,
            5000000
        ];
        $loan_exp_ms = [
            4,6,10,12,12,20,20,24,20,20
        ];
        $total_lmps = [
            50000,
            240000,
            500000,
            720000,
            900000,
            2000000,
            2200000,
            3000000,
            2200000,
            2200000
        ];
        $lmp_months = [
            4,6,10,12,12,20,20,24,20,20
        ];
        for($i=0; $i < count($names); ++$i){
            Rank::create([
                'id'=>$i+1,
                'name'=>$names[$i],
                'prize'=>$prizes[$i],
                'loan'=>$loans[$i],
                'pv'=>$pvs[$i],
                'loan_exp_m'=>$loan_exp_ms[$i],
                'total_lmp'=>$total_lmps[$i],
                'lmp_months'=>$lmp_months[$i]
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
        Schema::dropIfExists('ranks');
    }
}
