<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
class PackageController extends Controller
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
        $packages = Package::all();
        return view('admin.package.index', compact('packages'));
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
        $package = Package::find($id);
        if($package){
            $this->validate($request, [
                'name'=>['required'],
                'amount'=>['required', 'numeric'],
                'pv'=>['required', 'numeric'],
                'h_token'=>['required', 'numeric'],
                'ref_pv'=>['required'],
                'ref_h_token'=>['required'],
                'ref_percent'=>['required'],
                'insurance'=>['required'],
                'gen'=>['required']
            ]);
            if($request->input('id')) return;
            $package->update($request->all());
            return back()->with('success', 'package updated');
        }
        return back()->with('error', 'package not found');
    }
}
