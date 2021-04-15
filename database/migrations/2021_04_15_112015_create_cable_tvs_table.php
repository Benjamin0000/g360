<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CableTv;
class CreateCableTvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cable_tvs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->float('comm_amt');
            $table->float('charge');
            $table->string('ref_amt'); //amount to go to referrals
            $table->timestamps();
        });

        $names = ['DSTV', 'GOTV', 'STARTIMES'];
        $codes = ['BPD-NGCA-AQA', 'BPD-NGCA-AQC', 'BPD-NGCA-AWA'];
        for($i = 0; $i < count($names); $i++){
            CableTv::create([
                'name'=>$names[$i],
                'code'=>$codes[$i],
                'comm_amt'=>40,
                'charge'=>100,
                'ref_amt'=>"5,3,2"
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
        Schema::dropIfExists('cable_tvs');
    }
}
