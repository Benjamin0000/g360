<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Bank;
class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->timestamps();
        });
        $names = [
            'Jaiz Bank'=>'',
            'Access Bank'=>'000014',
            'MAINSTREET BANK PLC'=>'',
            'DIAMOND BANK PLC'=>'000005',
            'Keystone Bank'=>'000002',
            'Fidelity Bank'=>'000007',
            'FCMB'=>'000003',
            'First Bank of Nigeria'=>'000016',
            'GTBank Plc'=>'000013',
            'Ecobank'=>'000010',
            'Skye Bank'=>'000008',
            'Enterprise Bank'=>'000019',
            'StanbicIBTC Bank'=>'000012',
            'Sterling Bank'=>'000001',
            'Union Bank'=>'000018',
            'United Bank for Africa'=>'000004',
            'Unity Bank'=>'000011',
            'StandardChartered'=>'000021',
            'Wema Bank'=>'000017',
            'Zenith Bank'=>'000015',
            'City Bank'=>'000009',
            'Heritage'=>'000020',
            'Coronation Merchant Bank'=>'060001',
            'TNPF Microfinance Bank'=>'070001',
            'Fortis Microfinance Bank'=>'070002',
            'Covenant'=>'070006',
            'ASOSavings'=>'090001',
            'JubileeLife'=>'090003',
            'Parralex'=>'090004',
            'Trustbond'=>'090005',
            'Safetrust Mortgage Bank'=>'090006',
            'FET'=>'100001',
            'Pagatech'=>'100002',
            'Parkway-ReadyCash'=>'100003',
            'Paycom'=>'100004',
            'Cellulant'=>'100005',
            'eTranzact'=>'100006',
            'Stanbic Mobile Money'=>'100007',
            'Eco Mobile'=>'100008',
            'GT Mobile'=>'100009',
            'Teasy Mobile'=>'100010',
            'Mkudi'=>'100011',
            'VTNetworks'=>'100012',
            'AccessMobile'=>'100013',
            'FBNMobile'=>'100014',
            'ChamsMobile'=>'100015',
            'FortisMobile'=>'100016',
            'Hedonmark'=>'100017',
            'Zenith Mobile'=>'100018',
            'Fidelity Mobile'=>'100019',
            'MoneyBox'=>'100020',
            'Eartholeum'=>'100021',
            'Sterling Mobile'=>'100022',
            'Visual ICT'=>'100023',
            'FSDH'=>'400001',
            'NIP Virtual Bank'=>'999999',
            'EKONDO MICROFINANCE BANK'=>'090097',
            'Providus Bank'=>'000023'
        ];
        foreach ($names as $key => $value){
            Bank::create(['name'=>$key, 'code'=>$value]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
