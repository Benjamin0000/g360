<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\User;
use App\Models\Partner;
use App\Models\PContract;
class PartnersController extends Controller
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
        $total_partners = Partner::count();
        $p = PContract::where('status', 0);
        $amt_invested = $p->sum('amount');
        $ext_amt = $p->sum('total_return');
        $credited = $p->sum('returned');
        $partners = Partner::latest()->paginate(10);
        return view('admin.partners.index', compact('partners', 
        'total_partners', 'amt_invested', 'ext_amt', 'credited'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'gnumber'=>['required', 'max:13'],
            'amount'=>['required', 'max:50'],
            'total_return'=>['required', 'max:50'],
            'type'=>['required']
        ]);
        if($user = User::where('gnumber', $request->gnumber)->first()){
            if(!in_array($request->type, [0,1]))
                return back()->with('error', 'Invalid type selected');

            if(Partner::where('gnumber', $user->gnumber)->exists())
                return back()->with('error', 'Partner already exists');
            
            $partner = Partner::create([
                'id'=>Helpers::genTableId(Partner::class),
                'user_id'=>$user->id,
                'gnumber'=>$user->gnumber,
                'type'=>$request->type
            ]);
            PContract::create([
                'id'=>Helpers::genTableId(PContract::class),
                'partner_id'=>$partner->id,
                'amount'=>$request->amount,
                'total_return'=>$request->total_return,
                'months'=>$request->duration
            ]);
            return back()->with('success', 'partner has been created');
        }
        return back()->with('error', 'user not found');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($partner = Partner::find($id)){
            $partner->s_credit = $request->signup_credits;
            $partner->f_credit = $request->finance_credits;
            $partner->e_credit = $request->eshop_credits;
            $partner->save();
            return back()->with('success', 'Partner credentials updated');
        }   
        return back()->with('error', 'partner not found');
    }
    /**
     * Contracts
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contract($id)
    {
        if($partner = Partner::find($id)){
            $contracts = PContract::where('partner_id', $partner->id)->latest()->paginate(10);
            return view('admin.partners.contract.index', compact('contracts', 'partner'));
        }   
        return back()->with('error', 'partner not found');
    }
    /**
     * Create Contracts
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createContract(Request $request, $id)
    {
        if($partner = Partner::find($id)){
            PContract::create([
                'id'=>Helpers::genTableId(PContract::class),
                'partner_id'=>$partner->id,
                'amount'=>$request->amount,
                'total_return'=>$request->total_return,
                'months'=>$request->duration
            ]);
            return back()->with('success', 'contract created');
        }
        return back()->with('error', 'contract not found');
    }
     /**
     * Destory contract
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyContract($id)
    {
        if($contract = PContract::find($id)){
            $contract->delete();
            return back()->with('success', 'contract deleted');
        }
        return back()->with('error', 'contract not found');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($partner = Partner::find($id)){
            $contracts = PContract::where('partner_id', $partner->id);
            if($contracts->exists()){
                foreach($contracts->get() as $contract)
                    $contract->delete();
            }
            $partner->delete();
            return back()->with('success', 'Partner deleted');
        }else{
            return back()->with('error', 'Partner not found');
        }
    }
}
