<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Agent;
use App\Models\User;
use App\Models\State;
use App\Models\AgentSetting;
class AgentsController extends Controller
{
    /**
     * Creates a new Controller instance
     *
     * @return void
    */
    public function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $new_requests = Agent::where('status', 0)->count();
        $agents = Agent::where('status', 1)->paginate(10);
        return view('admin.agents.index', compact('new_requests', 'agents'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'state'=>['required'],
            'city'=>['required']
        ]);
        $user = User::where('gnumber', $request->gnumber)->first();
        if($user){
            if($user->agent){
                return back()->with('error', 'User already an agent');
            }else{
                $valid = Helpers::validStateAndCity($request->state, $request->city);
                if(is_array($valid)){
                    $ref_by = '';
                    $upline = $user->upline();
                    if($upline && $upline->agent)
                        $ref_by = $upline->agent->id;
                    Agent::create([
                        'id'=>Helpers::genTableId(Agent::class),
                        'user_id'=>$user->id,
                        'status'=>1,
                        'state_id'=>$valid['state_id'],
                        'city_id'=>$valid['city_id'],
                        'ref_by'=>$ref_by
                    ]);
                    return back()->with('success', 'Agent created');
                }else{
                    return back()->with('error', 'Invalid state or city');
                }
            }
        }else{
            return back()->with('error', 'User not found');
        }
    }
    /**
     *New Agents
     */
    public function newAgents()
    {
        $agents = Agent::where('status', 0)->paginate(10);
        return view('admin.agents.requests', compact('agents'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approveRequest($id)
    {
        $agent = Agent::where([ ['status', 0], ['id', $id] ])->first();
        if($agent){
            $agent->status = 1;
            $agent->save();
            return back()->with('success', 'agent approved');
        }else{
            return back()->with('error', 'Agent not found');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disApproveRequest($id)
    {
        $agent = Agent::where([ ['status', 0], ['id', $id] ])->first();
        if($agent){
            $agent->delete();
            return back()->with('success', 'Agent disapproved');
        }else{
            return back()->with('error', 'Agent not found');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.agents.settings.index');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAgent(Request $request)
    {
        if($setting = AgentSetting::first()){
        }else{
            $setting = new AgentSetting();
        }
        $setting->ag_prgc = (float)$request->prgc;
        $setting->ag_sra = (float)$request->ramt;
        $setting->ag_pfbc = (float)$request->pfbc;
        $setting->ag_pgsc = (float)$request->pgsc;
        $setting->ag_posc = (float)$request->posc;
        $setting->save();
        return back()->with('success', 'Agent settings updated');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSuperAgent(Request $request)
    {
        if($setting = AgentSetting::first()){
        }else{
            $setting = new AgentSetting();
        }
        $setting->sg_trprgc = (float)$request->rpg;
        $setting->sg_prgc = (float)$request->prgc;
        $setting->sg_pfbc = (float)$request->pfbc;
        $setting->sg_pgsc = (float)$request->pgsc;
        $setting->sg_posc = (float)$request->posc;
        $setting->save();
        return back()->with('success', 'Agent settings updated');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function makeSuper($id)
    {
        $agent = Agent::where('id', $id)->first();
        if($agent){
            if($agent->type == 1){
                $agent->type = 0;
            }else{
                $agent->type = 1;
            }
            $agent->save();
            return back()->with('success', 'Agent is now a super agent');
        }
        return back()->with('error', 'Agent not found');
    }
}
