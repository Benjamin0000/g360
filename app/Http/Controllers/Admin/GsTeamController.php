<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GTR;
use App\Models\GsClub;
class GsTeamController extends Controller
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
        $levels = GTR::all();
        return view('admin.gsteam.index', compact('levels'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        $gtrs = GTR::all();
        return view('admin.gsteam.settings.index', compact('gtrs'));
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
            'amount'=>['required', 'numeric'],
            'pay_back'=>['required', 'numeric'],
            'r_count'=>['required', 'numeric'],
            'g_hours'=>['required', 'numeric'],
            'r_hours'=>['required', 'numeric'],
            'total_ref'=>['required', 'numeric']
        ]);
        if($request->input('level')) return;
        if($gtr = GTR::find($id)){
            $gtr->update($request->all());
            return back()->with('success', 'GSteam updated');
        }   
        return back()->with('error', 'Not found');
    }
    public function show($id, $type)
    {
        $gtr = GTR::find($id);
        $gsclubs = GsClub::where([
            ['gbal', $gtr->amount],
            ['g', $type]
        ])->orderBy('created_at', 'ASC')->paginate(20);
        return view('admin.gsteam.show', compact('gsclubs', 'gtr', 'type'));
    }
}
