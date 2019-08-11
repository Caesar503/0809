<?php

namespace App\Http\Controllers\Care;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    public function carShow()
    {
        $uid = Auth::id();
        $data = Cart::where(['uid'=>$uid,'is_status'=>1])->get()->toArray();
        if($data)
        {
            return view('Cart.carShow',['res'=>$data,'uid'=>$uid]);
        }else{
            return view('Cart.carShow1');
        }

    }

    public function carDel(Request $request)
    {
        $res = $request->all();
        $r = Cart::where($res)->delete();
        if($r)
        {
            return 1;
        }else{
            return 0;
        }
    }
    public function carUpdateNum(Request $request)
    {
        $uid = Auth::id();
        $g = $request->all();
        $g_id = $g['id'];
        $j_num = $g['num'];
        //修改购物车中该商品的数量
        $c = Cart::where(['uid'=>$uid,'id'=>$g_id])->update(['num'=>$j_num]);
        if($c)
        {
            return 1;
        }else{
            return 0;
        }
    }
}
