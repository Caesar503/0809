<?php

namespace App\Http\Controllers;

use App\Model\Order;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Location;

class PayController extends Controller
{
    public $app_id;
    public $gate_way;
    public $notify_url;
    public $return_url;
    public $rsaPrivateKeyFilePath;
    public $aliPubKey;
    public function __construct()
    {
        $this->app_id = env('PAY_APP_ID');
        $this->gate_way = 'https://openapi.alipaydev.com/gateway.do';
//        $this->notify_url = env('PAY_NOTIFY_URL');
//        $this->return_url = env('PAY_RETURN_URL');
        $this->rsaPrivateKeyFilePath = openssl_pkey_get_private("file://".storage_path('app/keys/private.pem'));    //应用私钥
        $this->aliPubKey =openssl_get_publickey("file://".storage_path('app/keys/pay_pub.pem')); //支付宝公钥
    }
    public function alipay($id)
    {
        //该订单的总价 + 订单号
        $order_data = Order::where('id',$id)->first()->toArray();

        //域名
        $yuming = env('YUMING');
        $object = '赵恺收款！！';
        //请求业务参数
        $content = [
            'subject'=> urlencode($object),
            'out_trade_no' => $order_data['order_sn'],
            'total_amount' => $order_data['order_amount'],
//            'product_code' => 'QUICK_WAP_WAY'
            'product_code' => 'FAST_INSTANT_TRADE_PAY'
        ];
        //公共参数
        $data = [
            'app_id' => $this->app_id,
//            'method' => 'alipay.trade.wap.pay',
            'method' => 'alipay.trade.page.pay',
            'format' => 'JSON',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s',time()),
            'version' => '1.0',
            'notify_url'   => 'http://'.$yuming.'/alipayNotify',       //异步通知地址
            'return_url'   => 'http://'.$yuming.'/alipayA',      // 同步通知地址
            'biz_content' => json_encode($content)
        ];
        //拼接参数
        ksort($data);
        $a = '';
        foreach($data as $k=>$v){
            $a.=$k.'='.$v.'&';
        }
        $b = rtrim($a,'&');
        //签名
        openssl_sign($b,$sign,openssl_pkey_get_private("file://".storage_path('app/keys/private.pem')),OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
        $data['sign']=$sign;
        //拼接url
        $a1='?';
        foreach($data as $k1=>$v1){
            $a1.=$k1.'='.urlencode($v1).'&';
        }
        $b1 = rtrim($a1,'&');
        $url = $this->gate_way.$b1;
        header('Location:'.$url);
    }
    //同步
    public function alipayA()
    {
        echo "支付成功！！！！";
        header('Location:/;Refresh:2');
    }
    public function alipayNotify()
    {
        $data = $_POST;
        $sign = $data['sign'];
        $trade_no = $data['out_trade_no'];
        //写入日志
        $log = "\n>>>>>>>>>>>".date('Y-m-d H:i:s',time())."\n".json_encode($data)."\n";
        file_put_contents("logs/notify.log",$log,FILE_APPEND);
        unset($data['sign']);
        unset($data['sign_type']);
        ksort($data);
        //获取等待签名的字符串
        $a = '';
        foreach($data as $k =>$v){
            $a.=$k.'='.$v.'&';
        }
        $aa =rtrim($a,'&');
        //验签
        $res = openssl_verify($aa,base64_decode($sign),$this->aliPubKey,OPENSSL_ALGO_SHA256);
        file_put_contents("logs/notify.log",$res."\n\n\n\n\n",FILE_APPEND);
        if($res){
            //TODO  处理逻辑业务
            //修改订单
            echo 'success';
        }else{
            echo 'fail';
        }
    }
}
