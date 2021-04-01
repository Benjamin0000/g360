<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DataSub;
class CreateDataSubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_subs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->float('comm'); //percentage commission
            $table->string('ref_amt'); //amount to go to referrals
            $table->timestamps();
        });
        $names = ['MTN', 'GLO', '9MOBILE', 'AIRTEL'];
        $logos = [
            'assets/download.png',
            'assets/download4.png',
            'assets/download3.png',
            'assets/download2.png'
        ];
        for($i = 0; $i < count($names); $i++){
            DataSub::create([
                'name'=>$names[$i],
                'logo'=>$logos[$i],
                'comm'=>2,
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
        Schema::dropIfExists('data_subs');
    }
}
