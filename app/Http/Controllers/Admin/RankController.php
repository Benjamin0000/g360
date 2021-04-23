<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rank;
class RankController extends Controller
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
        $ranks = Rank::all();
        $total = $ranks->count();
        return view('admin.rank.index', compact('ranks','total'));
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
        $this->validate($request, [
            'name'=>['required'],
            'prize'=>['required', 'numeric'],
            'loan'=>['required', 'numeric'],
            'pv'=>['required', 'numeric'],
            'loan_exp_months'=>['required', 'numeric'],
            'lmp'=>['required', 'numeric'],
            'lmp_exp_months'=>['required', 'numeric'],
            'carry_over'=>['required', 'numeric'],
            'loan_interest'=>['required', 'numeric'],
            'loan_grace_interest'=>['required', 'numeric'],
            'loan_grace_expiry_month'=>['required', 'numeric']
        ]);
        $rank = Rank::find($id);
        if($rank->id == 1){
            $this->validate($request, [
                'fee'=>['required', 'numeric'],
                'graced_minutes'=>['required', 'numeric'],
                'minutes'=>['required', 'numeric'],
            ]);
            $rank->fee = $request->fee;
            $rank->minutes = $request->minutes;
            $rank->graced_minutes = $request->graced_minutes;
        }
        $rank->name = $request->name;
        $rank->prize = $request->prize;
        $rank->loan = $request->loan;
        $rank->pv = $request->pv;
        $rank->loan_exp_m = $request->loan_exp_months;
        $rank->total_lmp = $request->lmp;
        $rank->lmp_months = $request->lmp_exp_months;
        $rank->carry_over = $request->carry_over;
        $rank->loan_interest = $request->loan_interest;
        $rank->loan_g_interest = $request->loan_grace_interest;
        $rank->loan_g_exp_m = $request->loan_grace_expiry_month;
        $rank->save();
        return back()->with('success', 'Rank updated');
    }
}
