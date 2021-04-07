<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers;
use App\Models\Agent;
class AgentController extends Controller
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
        if(!Auth::user()->agent)
            return redirect(route('user.agent.apply'));
        return view('user.agent.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apply()
    {
        if(Auth::user()->agent)
            return redirect(route('user.agent.index'));
        return view('user.agent.apply');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if($user->agent)
            return redirect(route('user.agent.index'));
        $this->validate($request, [
            'state'=>['required'],
            'city'=>['required']
        ]);
        $valid = Helpers::validStateAndCity($request->state, $request->city);
        if(is_array($valid)){
            $ref_by = '';
            $upline = $user->upline();
            if($upline && $upline->agent)
                $ref_by = $upline->agent->id;   
            $agent = Agent::create([
                'id'=>Helpers::genTableId(Agent::class),
                'user_id'=>Auth::id(),
                'state_id'=>$valid['state_id'],
                'city_id'=>$valid['city_id'],
                'ref_by'=>$ref_by
            ]);
            return back()->with('success', 'Agent Request Submitted');
        }else{
            return back()->with('error', 'Invalid state or city');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteRequest($id)
    {
        $agent = Auth::user()->agent;
        if($agent && $agent->status == 0 && $agent->credited == 0){
            $agent->delete();
            return back()->with('success', 'request canceled');
        }
        return back()->with('error', 'not found');
    }
}
