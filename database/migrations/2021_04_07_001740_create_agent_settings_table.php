<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AgentSetting;
class CreateAgentSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_settings', function (Blueprint $table) {
            $table->id();
            $table->float('ag_prgc')->default(0); #price per RGold coin
            $table->float('ag_sra')->default(0); #signup ref amt
            $table->float('sg_trprgc')->default(0); #total referral per RGcoin
            $table->float('sg_prgc')->default(0); #super agent Price per RGold coin

            $table->float('ag_pfbc')->default(0); #bronz coin
            $table->float('ag_pgsc')->default(0); #siver coin
            $table->float('ag_posc')->default(0); #diamon coin POS

            $table->float('sg_pfbc')->default(0); #bronz coin
            $table->float('sg_pgsc')->default(0); #siver coin
            $table->float('sg_posc')->default(0); #diamon coin POS
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
        Schema::dropIfExists('agent_settings');
    }
}
