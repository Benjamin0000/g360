<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PartnerProfit;
class CreatePartnerProfitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner_profits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('users_count')->default(0);
            $table->bigInteger('last_count')->default(0);
            $table->timestamps();
        });
        PartnerProfit::create([
            'name'=>'partner'
        ]);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partner_profits');
    }
}
