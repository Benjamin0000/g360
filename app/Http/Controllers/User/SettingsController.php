<?php
namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
