<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Shop;
use App\Models\State as Place;
use App\Models\ShopCategory;
use App\Models\ProductCategory;
class ShopController extends Controller
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
        $shops = Shop::where('user_id', Auth::id())->paginate(10);
        return view('user.gmarket.shop.index', compact('shops'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ShopCategory::all();
        return view('user.gmarket.shop.create.index', compact('categories'));
    }
    /**
     * Get Cities
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getCities(Request $request)
    {
        if($request->ajax()){
            if(!$type = $request->t)return 0;
            if($state = $request->s){
                $cities = Helpers::getCities($state);
                if(is_array($cities) && count($cities) > 0){
                    if($type == 'c'){
                        $view = view('user.gmarket.shop.create.city_option', compact('cities'));
                    }elseif($type == 'p'){
                        $view = view('user.gmarket.shop.create.loc_option', compact('cities'));
                    }else{return;}
                    return ['data'=>"$view"];
                }
            }  
        }
        return 0;
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
            'name'=>['required', 'max:200', 'unique:shops'],
            'logo'=>['required', 'mimes:jpeg,bmp,png,jpg'],
            'category'=>['required', 'max:100'],
            'state'=>['required', 'max:100'],
            'city'=>['required'],
            'phone_number'=>['required'],
            'address'=>['required', 'max:500']
        ]);
        $state = $request->state;
        $category = $request->category;
        $city = $request->city;
        $location = $request->location;

        $logo = $request->file('logo');
        $logoName = strtolower($logo->getClientOriginalName());
        if(($logo->getSize()/1000) > 100)
             return back()->with('error', 'Logo size must not be greater than 100kb');

        // $logoName = Helpers::fileRand($logoName);
        $checkState = Place::where('name', $state)->first();
        $checkCity = Place::where('name', $city)->first();

        if(!$checkState)
            return back()->with('error', 'Invalid state selected');
        if(!$checkCity || $checkCity->parent_id != $checkState->id)
            return back()->with('error', 'Invalid city selected');

        if($location){
            $checkLocation = Place::where([ 
                ['name', $location],
                ['parent_id', $checkCity->id]
            ])->first();
            if(!$checkLocation)
                return back()->with('error', 'Invalid location selected');  
        }

        $checkCategory = ShopCategory::where('name', $category)->first();
        if(!$checkCategory)
            return back()->with('error', 'Invalid category selected');

        $path = Storage::disk('do')->putFile('images', $logo);

        if(!$location){
            $checkLocation = '';
        }
        Shop::create([
            'id'=>Helpers::genTableId(Shop::class),
            'user_id'=>Auth::id(),
            'name'=>$request->name,
            'shop_category_id'=>$checkCategory->id,
            'logo'=>$path,
            'state_id'=>$checkState->id,
            'city_id'=>$checkCity->id,
            'location_id'=>$checkLocation?$checkLocation->id:null,
            'phone_number'=>$request->phone_number,
            'address'=>$request->address
        ]);
        return redirect(route('user.shop.index'))->with('success', 'Shop created');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();
        if($shop){
            $categories = ShopCategory::all();
            return view('user.gmarket.shop.edit', compact('shop', 'categories'));
        }
        return back()->with('error', 'Shop not found');
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
            'name'=>['required', 'max:200'],
            'category'=>['required', 'max:100'],
            'state'=>['required', 'max:100'],
            'city'=>['required'],
            'phone_number'=>['required'],
            'address'=>['required', 'max:500']
        ]);
        $user = Auth::user();
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();
        if(!$shop)
            return back()->with('error', 'Not found');
        $data = $request->all();
        $e_n = Shop::where([
            ['id', '<>', $id],
            ['name', $request->name], 
        ])->exists();
        if($e_n)
            return back()->with('error', 'Shop name already exists');
        $state = $request->state;
        $category = $request->category;
        $city = $request->city;
        $location = $request->location;

        $checkState = Place::where('name', $state)->first();
        $checkCity = Place::where('name', $city)->first();

        if(!$checkState)
            return back()->with('error', 'Invalid state selected');
        if(!$checkCity || $checkCity->parent_id != $checkState->id)
            return back()->with('error', 'Invalid city selected');

        if($location){
            $checkLocation = Place::where([ 
                ['name', $location],
                ['parent_id', $checkCity->id]
            ])->first();
            if(!$checkLocation)
                return back()->with('error', 'Invalid location selected');  
            
            $data['location_id'] = $checkLocation ? $checkLocation->id:null;
        }
        $checkCategory = ShopCategory::where('name', $category)->first();
        if(!$checkCategory)
            return back()->with('error', 'Invalid category selected');

        if(!$location)
            $checkLocation = '';
        
        $data['shop_category_id'] = $checkCategory->id;
        $data['state_id'] = $checkState->id;
        $data['city_id'] = $checkCity->id;

        if($request->file('logo')){
            $this->validate($request, [
                'logo'=>['required', 'mimes:jpeg,bmp,png,jpg']
            ]);
            $logo = $request->file('logo');
            $logoName = strtolower($logo->getClientOriginalName());
            if(($logo->getSize()/1000) > 100)
                 return back()->with('error', 'Logo size must not be greater than 100kb');
            
            if(Storage::disk('do')->exists($shop->logo))
                Storage::disk('do')->delete($shop->logo);
            
            $path = Storage::disk('do')->putFile('images', $logo);
            $data['logo'] = $path;
        }
        $shop->update($data);
        return back()->with('success', 'Shop updated');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();
        if(!$shop)
            return back()->with('error', 'Not found');
        #delete products
        return back()->with('error', 'We are working on this feature');
    }
    /**
     * Category
     *
     * @return \Illuminate\Http\Response
     */
    public function category($id)
    {
        $user = Auth::user();
        $shop = Shop::where([
            ['user_id', $user->id], 
            ['id', $id]  
        ])->first();
        if(!$shop)
            return back()->with('error', 'Not found');

        $categories = ProductCategory::where([
            ['user_id', $user->id],
            ['shop_id', $shop->id]
        ])->get();
        return view('user.gmarket.shop.category.index', compact('shop', 'categories'));
    }
    /**
     * Category
     *
     * @return \Illuminate\Http\Response
     */
    public function saveCategory(Request $request)
    {
        
    }

}
