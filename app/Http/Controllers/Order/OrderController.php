<?php

namespace App\Http\Controllers\Order;

use App\Model\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Model\Order;
use App\Model\Orderdetail;
use App\Model\Goods;
class OrderController extends Controller
{
    //添加到订单
    public function addOrder(Request $request)
    {
       $req = $request->all();
       //订单中的商品id
        $g_id = $req['gid'];
        $g_id = rtrim($g_id,',');
        $g_id = explode(',',$g_id);

        //用户id
        $uid = Auth::id();

        //添加到订单表中
        $order_data['order_sn'] = '1809a_test'.Str::random(16);
        $order_data['uid'] = $uid;
        $order_data['order_amount'] = $req['allPrice'];

        $o_id = Order::insertGetId($order_data);

        //添加到订单详情表中
        foreach($g_id as $v)
        {
            $o_data = Cart::where('id',$v)->first()->toArray();
            $orderdetail_data []=[
                'oid'=>$o_id,
                'goods_id'=>$v,
                'goods_name'=>$o_data['goods_name'],
                'goods_price'=>$o_data['goods_price'],
                'num'=>$o_data['num'],
                'store_id'=>$o_data['store_id'],
                'uid'=>$uid
            ];
        }
        $res1 = Orderdetail::insert($orderdetail_data);

        //将购车表中的数据删除
        $res2 = Cart::where(['uid'=>$uid,'is_status'=>1])->update(['is_status'=>2]);
        if($res1&&$res2&&$o_id)
        {
            return 1;
        }else{
            return 0;
        }
    }
    //订单列表
    public function orderShow()
    {
        $uid = Auth::id();
        $data = Order::where('uid',$uid)->get()->toArray();
        if($data)
        {
            return view('Order.orderShow',['res'=>$data]);
        }else{
            return view('Order.orderShow1');
        }
    }
    //订单详情
    public function orderdetail($id)
    {

        //获取商户的id
        $store_id = Orderdetail::where('oid',$id)->select('store_id')->get()->toArray();
        foreach ($store_id as $v)
        {
            $store_id_two[] = $v['store_id'];
        }
        $store_id_three = array_unique($store_id_two);

        //获取每个商户的商品
        foreach ($store_id_three as $v1)
        {
            $orderDetail_data[] =Orderdetail::where(['oid'=>$id,'store_id'=>$v1])->get()->toArray();
        }


        return view('Order.orderDetailShow',['res'=>$orderDetail_data,'id'=>$id]);
    }
}
