<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\G360;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\GsClub;
use App\Models\GsClubH;
class GsClubController extends G360
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
        $user = Auth::user();
        $member = GsClub::where([ 
            ['user_id', $user->id], 
            ['status', 0]   
        ])->first();
        $histories = GsClubH::where('user_id', $user->id)->paginate(10);
        $total_his = $histories->count();
        return view('user.gsclub.index', compact('member', 'histories', 'total_his'));
    }
    /**
     * Get more histories
     *
     * @return \Illuminate\Http\Response
     */
    public function moreHistories(Request $request)
    {
        if($request->ajax()){
            $user = Auth::user();
            $histories = GsClubH::where('user_id', $user->id)->paginate(10);
            $total_his = $histories->count();
            $cur = self::$cur;
            $view = view('user.gsclub.table_tr', compact('histories', 'total_his', 'cur'));
            if($view){
                return ['data'=>"$view"];
            }
            return 0;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
