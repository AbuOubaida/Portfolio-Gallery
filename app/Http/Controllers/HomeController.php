<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\Auth;
//use Illuminate\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Session::has('url'))
        {
            $url = Session::get('url');
            Session::flash('url');
        }
        else{
            $url ='/';
        }
        return redirect($url);
    }
    public function evolutionStatus($photo_id,$name/*empty name return dislike status*/)
    {
        if (!empty($name) && ($name == 'like') || ($name == 'l') || ($name == 'lk'))$evl_name = 'like_status';
        else if (!empty($name) && ($name == 'dislike') || $name == 'unlike' || $name == 'ulk')$evl_name = 'dislike_status';
        else $evl_name = 'dislike_status';
        $evldata = DB::table('photo_evolution')->where('photo_id',$photo_id)->where($evl_name,1)->get()->count();
        return $evldata;
    }
    public function evolutionSingleStatus($photo_id,$owner_id,$name/*empty name return dislike status*/)
    {
        if (!empty($name) && ($name == 'like') || $name == 'l' || $name == 'lk')$evl_name = 'like_status';
        else if (!empty($name) && ($name == 'dislike') || $name == 'unlike' || $name == 'ulk')$evl_name = 'dislike_status';
        else $evl_name = 'dislike_status';
        $evldata = DB::table('photo_evolution')->where('photo_id',$photo_id)->where($evl_name,1)->where('user_id',$owner_id)->get()->count();
        return $evldata;
    }
    public function currentPage()
    {
        $url = url()->current();
        Session::put('url',$url);
    }
}
