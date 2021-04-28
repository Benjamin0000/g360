<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Models\User;
class DownlineController extends Controller
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
    public function direct()
    {
        $user = Auth::user();
        $referals = User::where('ref_gnum', $user->gnumber)
        ->orWhere('placed_by', $user->gnumber)
        ->orderBy('cpv', 'DESC')
        ->latest()->paginate(10);
        return view('user.downline.direct', compact('referals'));
    }
    /**
     * Recursively get all indirect referrals
     *
     * @return \Illuminate\Http\Response
     */   
    private static function getRefGen($gnumber, &$referals, $level=1)
    {
        if($level > 15) return;
        $user = User::where('ref_gnum', $gnumber)
        ->orWhere('placed_by', $gnumber)->first();
        if($user){
            $user['level'] = $level;
            array_push($referals, $user);
            self::getRefGen($user->gnumber, $referals, $level+1);
        }else {
            return;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indirect()
    {
        $user = Auth::user();
        $referals = [];
        $d_referals = User::where('ref_gnum', $user->gnumber)->latest()->get();
        if($d_referals->count()){
            foreach($d_referals as $d_referal){
                self::getRefGen($d_referal->gnumber, $referals);
            }
        }
        $referals = $this->paginate($referals, 10,);
        return view('user.downline.indirect', compact('referals'));
    }
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $paginator = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        $paginator->setPath(route('user.downline.indirect'));
        // $paginator->setPageName("p");
        return $paginator;
    }

}
