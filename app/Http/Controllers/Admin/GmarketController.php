<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\ShopCategory;
class GmarketController extends Controller
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shop()
    {
        $shops = Shop::paginate(10);
        return view('admin.gmarket.shop.index', compact('shops'));
    }
    /**
     * Show category page
     *
     * @return \Illuminate\Http\Response
     */
    public function shopCategory()
    {
        $categories = ShopCategory::all();
        return view('admin.gmarket.shop.category.index', compact('categories'));
    }
    /**
     * Create shop category
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createShopCategory(Request $request)
    {
        $this->validate($request, [
            'name'=>['required', 'unique:shop_categories']
        ]);
        ShopCategory::create($request->all());
        return back()->with('success', 'category created');
    }
    /**
     * Update shop category
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateShopCategory(Request $request, $id)
    {
        if($category = ShopCategory::find($id)){
            $category->update($request->all());
            return back()->with('success', 'Category updated');
        }
        return back()->with('error', 'category not found');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteShopCategory($id)
    {
        if($category = ShopCategory::find($id)){
            if(!$category->shops->count()){
                $category->delete();
                return back()->with('success', 'Category Deleted');
            }
            return back()->with('error', "Can't delete category, there are shops registered under this category");
        }
        return back()->with('error', 'category not found');
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
