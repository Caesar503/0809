<?php

namespace App\Http\Controllers\Goods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Goods;
use Illuminate\Support\Facades\Auth;
use App\Model\Cart;
class GoodsController extends Controller
{
    public function goodsShow()
    {
        $res = Goods::where(['is_up'=>1])->get();
        $uid= Auth::id();
        return view('Goods/showGoods',['res'=>$res,'uid'=>$uid]);
    }
    //添加购物车
    public function addCare(Request $request)
    {
        $res = $request->all();
        $id = $res['id'];
        $uid = $res['uid'];

        //加入购物车
        $c_data = Cart::where('id',$id)->first();
        if($c_data)
        {
            $r_id = Cart::where('id',$id)->update(['num'=>$c_data['num']+1]);
        }else{
            //通过id 获取该商品的数据
            $g_data = Goods::where('id',$id)->first()->toArray();

            $cart_data['uid']= $uid;
            $cart_data['id'] = $g_data['id'];
            $cart_data['goods_name'] = $g_data['goods_name'];
            $cart_data['goods_price'] = $g_data['self_price'];
            $cart_data['store_id'] = $g_data['cate_id'];
            $cart_data['goods_num'] = $g_data['goods_num'];
            $r_id = Cart::insertGetId($cart_data);
        }

        if($r_id)
        {
            return 1;
        }else{
            return 0;
        }
    }
}
