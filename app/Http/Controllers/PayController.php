<?php

namespace App\Http\Controllers;

use App\Model\Order;
use Illuminate\Http\Request;

class PayController extends Controller
{
    public function alipay($id)
    {
        //该订单的总价 + 订单号
        $order_data = Order::where('id',$id)->first()->toArray();

        //域名
        $yuming = env('YUMING');


//        echo "<pre>";print_r($_SERVER);echo "<pre>";die;
        $str2 = json_encode($_SERVER['HTTP_USER_AGENT']);
        $str = 'Windows';
        if(strpos($str2,$str)){
            //扫码支付
            $method = 'alipay.trade.page.pay';
            $prouct_code = 'FAST_INSTANT_TRADE_PAY';
            $url = 'https://openapi.alipaydev.com/gateway.do';
        }else{
            //h5支付
            $method = 'alipay.trade.wap.pay';
            $prouct_code = 'QUICK_WAP_WAY';
            $url = 'https://openapi.alipaydev.com/gateway.do';
        }
        //业务参数
        $bizcont = [
            'subject' => '1809a赵恺',//交易标题/订单标题/订单关键
            'out_trade_no'=>$order_data['order_sn'], //订单号
            'total_amount'      => $order_data['order_amount'] / 100, //支付金额
            'product_code'      => $prouct_code //固定值
        ];
        //公共参数
        $data = [
            'app_id'   => '2016092600600354',
            'method'   => $method,
            'format'   => 'JSON',
            'charset'   => 'utf-8',
            'sign_type'   => 'RSA2',
            'timestamp'   => date('Y-m-d H:i:s',time()),
            'version'   => '1.0',
            'notify_url'   => 'http://'.$yuming.'/alipayNotify',       //异步通知地址
            'return_url'   => 'http://'.$yuming.'/alipayA',      // 同步通知地址
            'biz_content'   => json_encode($bizcont),
        ];
        //拼接参数
        ksort($data);//根据键以升序对关联数组进行排序
        $i = "";
        foreach ($data as $k=>$v)
        {
            $i.=$k.'='.$v.'&';
        }
        $trim  = rtrim($i,'&');
//        var_dump($trim);die;
        //生成签名 最后拼接为url 格式
        $rsaPrivateKeyFilePath = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem')); //密钥
//           var_dump($rsaPrivateKeyFilePath);
//            $a = openssl_error_string();
//            var_dump($a);die;
        //生成签名
        openssl_sign($trim,$sign,$rsaPrivateKeyFilePath,OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);
//        echo $sign;die;
        $data['sign']=$sign;
        //拼接url
        $a='?';
        foreach($data as $key=>$val){
            $a.=$key.'='.urlencode($val).'&'; //urlencode 将字符串以url形式编码
        }
        $trim2 = rtrim($a,'&');
//        var_dump($trim2);die;
        $url2 = $url.$trim2;
        header('refresh:2;url='.$url2);
    }
    public function alipayA()
    {
        echo '支付成功：三秒后跳至首页';
    }

    //异步回调
    public function alipayNotify()
    {
        $p = json_encode($_POST);
        $data=json_decode($p,true);
        $log_str = "\n>>>>>> " .date('Y-m-d H:i:s') . ' '.$p . " \n";
        is_dir('logs') or mkdir('logs', 0777, true);
        file_put_contents('logs/alipay_notify',$log_str,FILE_APPEND);
        echo 'success';
        //TODO 验签 更新订单状态
    }
}
