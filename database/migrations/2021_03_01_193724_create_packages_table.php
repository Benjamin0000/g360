<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Package;
class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->integer('id')->primary(); //[ Never you change this ]; Very important for package listing if you do the whole package logic will malfunction in the whole system
            $table->string('name');
            $table->decimal('amount', 10, 2)->default(0);
            $table->integer('pv');
            $table->integer('h_token');
            $table->string('ref_pv'); // point value for each generation (an array is stored)
            $table->string('ref_h_token'); // health token for each generation (an array is stored)
            $table->string('ref_percent'); // referal commission in percent for each generation (an array is stored)
            $table->tinyinteger('insurance'); //insurance in months
            $table->tinyinteger('gen'); // generation
            $table->timestamps();
        });

        $names = ['free', 'basic', 'standard', 'super', 'master', 'professional', 'vip'];
        $amount = [0, 18750, 54750, 104750, 174750, 254750, 354750];
        #this is levels against package
        $rev_pv = [
            '[]',
            '[60,50,40,30]', 
            '[120,100,80,60]', 
            '[180,150,120,90]', 
            '[240,200,160,120]', 
            '[300,250,200,150]', 
            '[360,300,240,180]'
        ];
        $ref_h_token = [
            '[]',
            '[5,3,2,1]',
            '[5,3,2,1]',
            '[5,3,2,1]',
            '[5,3,2,1]',
            '[5,3,2,1]',
            '[5,3,2,1]'
        ];
        $ref_percent = [
            '[6,2]',
            '[6, 2, 1.2, 0.4]',
            '[6, 2, 1.2, 0.4]',
            '[6, 2, 1.2, 0.4]',
            '[6, 2, 1.2, 0.4]',
            '[6, 2, 1.2, 0.4]',
            '[6, 2, 1.2, 0.4]',
        ];
        $insurance = [
            0,3,4,5,12,12,12
        ];
        $pv = [0,80,160,240,320,400,480];
        $h_token = [0,8,8,8,8,8,8,8];
        $gen = [2,4,6,8,10,12,15];
        for($i=1; $i<=count($names); ++$i){
            Package::create([
                'id'=>$i,
                'name'=>$names[$i-1],
                'amount'=>$amount[$i-1],
                'pv'=>$pv[$i-1],
                'h_token'=>$h_token[$i-1],
                'ref_pv'=>$rev_pv[$i-1],
                'ref_h_token'=>$ref_h_token[$i-1],
                'ref_percent'=>$ref_percent[$i-1],
                'insurance'=>$insurance[$i-1],
                'gen'=>$gen[$i-1]
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
        Schema::dropIfExists('packages');
    }
}
