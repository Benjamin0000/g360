<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\EDisco;
class CreateEDiscosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('e_discos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->float('comm_amt'); //percentage commission
            $table->float('charge');
            $table->string('ref_amt'); //amount to go to referrals
            $table->timestamps();
        });
        $names = [
            'Abuja Postpaid', 
            'Abuja Prepaid', 
            'Eko PHCN', 
            'Enugu Distribution',
            'Ibadan Distribution', 
            'Ikeja Electric', 
            'Kaduna', 
            'Kano Postpaid', 
            'Kano Prepaid', 
            'Port Harcourt Postpaid', 
            'Port Harcourt Prepaid'
        ];
        $codes = [
            'BPE-NGCABABB-OR', 
            'BPE-NGCABABA-OR', 
            'BPE-NGEK-OR', 
            'BPE-NGEN-OR', 
            'BPE-NGIB-OR', 
            'BPE-NGIE-OR',
            'BPE-NGKD-OR', 
            'BPE-NGCAAVC-OR', 
            'BPE-NGCAAVB-OR', 
            'BPE-NGCABIB-OR', 
            'BPE-NGCABIA-OR'
        ];
        for($i = 0; $i < count($names); $i++){
            EDisco::create([
                'name'=>$names[$i],
                'code'=>$codes[$i],
                'comm_amt'=>40,
                'charge'=>100,
                'ref_amt'=>'5,3,2'
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
        Schema::dropIfExists('e_discos');
    }
}
