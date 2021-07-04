<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Shop;
use App\Models\Product;
class ProductController extends Controller
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
    public function index($id)
    {
        $user = Auth::user();
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();

        if(!$shop)
            return back()->with('error', 'Not found');

        $products = Product::where([ 
            ['shop_id',  $id],
            ['user_id', $user->id]
        ])->paginate(10);

        return view('user.gmarket.shop.product.index', compact('products', 'shop'));
    }
    public function create($id)
    {
        $user = Auth::user();
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();

        if(!$shop)
            return back()->with('error', 'Not found');

        return view('user.gmarket.shop.product.create', compact('shop'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}