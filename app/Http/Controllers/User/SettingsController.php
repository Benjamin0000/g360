<?php
namespace App\Http\Controllers\User;
use App\Http\Helpers;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\Monnify;
class SettingsController extends Controller
{
    /**
    * Creates a new controller instance
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.settings.index');
    }
    /**
     *update nin number
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateNin(Request $request)
    {
        $this->validate($request, [
            'nin_number'=>['required']
        ]);
        $user = Auth::user();
        $user->nin_number = $request->nin_number;
        $user->save();
        return back()->with('success', 'NIN number saved');
    }
    /**
     *update BVN
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addBvn(Request $request)
    {
        $this->validate($request, [
            'bvn'=>['numeric', 'digits:11', 'unique:virtual_accounts']
        ]);
        $user = Auth::user();
        $bvn = $request->bvn;
        $name = $user->fname.' '.$user->lname;
        $email = $user->email;
        if(!$user->virtualAccount){
            $mn = new Monnify();
            $data = $mn->createAccount($bvn, $name, $email);
            if( isset($data['responseBody']) && isset($data['responseBody']['accountNumber']) ){
                $body = $data['responseBody'];
                VirtualAccount::create([
                    'id'=>Helpers::genTableId(VirtualAccount::class),
                    'user_id'=>$user->id,
                    'reference'=>$body['accountReference'],
                    'number'=>$body['accountNumber'],
                    'bvn'=>$bvn,
                    'bank_code'=>$body['bankCode'],
                    'bank_name'=>$body['bankName']
                ]);
                return back()->with('success', 'Bvn added');
            }
            return back()->with('error', "Operation could not be completed, Try again later");
        }
        return back()->with('error', 'You can\'t access that resource');
    }
    /**
     * update Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'password'=>['required', 'confirmed'],
            'current_password'=>['required']
        ]);
        $user = Auth::user();
        if(!password_verify($request->current_password, $user->password))
            return back()->with('error', 'Incorrect account password');
        $user->password = Hash::make($request->password);
        $user->save();
        return back()->with('success', 'password updated');
    }
}
