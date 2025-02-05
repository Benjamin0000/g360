<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Airtime;
class CreateAirtimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airtimes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo');
            $table->float('min_buy');
            $table->float('max_buy');
            $table->float('comm'); //percentage commission
            $table->string('ref_amt'); //amount to go to referrals
            $table->timestamps();
        });
        $names = ['MTN', 'Globacom', '9mobile', 'Airtel'];
        $logos = [
            'assets/download.png',
            'assets/download4.png',
            'assets/download3.png',
            'assets/download2.png'
        ];
        // $product_code = ['MFIN-5-OR','MFIN-6-OR','MFIN-2-OR','MFIN-1-OR'];
        for($i = 0; $i < count($names); $i++){
            Airtime::create([
                'name'=>$names[$i],
                'logo'=>$logos[$i],
                'min_buy'=>50,
                'max_buy'=>50000,
                'comm'=>2,
                'ref_amt'=>'5,3,2',
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
        Schema::dropIfExists('airtimes');
    }
}
