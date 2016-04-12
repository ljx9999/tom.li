<?php
namespace Home\Controller;
use Think\Controller;
class AlipayController extends Controller {
    public function _initialize(){
        header("Content-type:text/html;charset=utf-8");
        import('Vendor.Alipay.alipayCore','','.php');
//      import('Vendor.Alipay.alipayMd5','','.php');
        import('Vendor.Alipay.alipayRsa','','.php');
        import('Vendor.Alipay.alipayNotify','','.php');
//      import('Vendor.Alipay.alipaySubmit','','.php');
    }

    public function mobilePay(){
        $orderId = I('order_id');
        $orderModel =M('order');
        $orderInfo = $orderModel->where('id='.$orderId)->find();
        //$this->checkPayLog($orderInfo['order_number'],$user_id);
        
        //订单号
        $salt = rand(10000,99999);
        $out_trade_no = $orderInfo['order_number']."_".$salt;
        $total_fee = (float)($orderInfo['quote']*0.015);
        $data['subject'] = '滴滴装修款项';
        $data['detail'] = '滴滴装修预付款项，具体事项请联系滴滴客服人员解答';
        $data['quote'] =  $total_fee;
        $data['trade_no'] = $out_trade_no;
        $sign = $this->alipayapi($data);
        echo $sign;
    }


    //alipay支付接口  //参数额外配置数组$configs
    public function alipayapi($configs){
        /****************************************************/
        //>>>>>>>>>>>>第一步
        //根据alipay源文件加载顺序依次加载配置
        $alipay_config = C('alipay_config');

        /**************************请求参数配置**************************/
        //支付类型
        $payment_type = C('alipay.payment_type');
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = C('alipay.notify_url');
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //卖家支付宝帐户
        $seller_email = C('alipay.seller_email');

     
        //接收动态订单数据
        //商户订单号
        $out_trade_no = $configs['trade_no'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $configs['subject'];
        //必填

        //付款金额
        $total_fee = $configs['quote'];
        //必填

        //订单描述
        $body = $configs['detail'];
       
     
        //>>>>>>>>>>>>第三步
        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => "mobile.securitypay.pay",
                "partner" => trim($alipay_config['partner']),
                "payment_type"  => $payment_type,
                'sign_type' => 'RSA',
                'sign' => '',
                "notify_url"    => $notify_url,
                "seller_id"  => $seller_email,
                "out_trade_no"  => $out_trade_no,
                "subject"   => $subject,
                "total_fee" => $total_fee,
                "body"  => $body,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );
        $private_key_path = getcwd().'/key/rsa_private_key.pem';
        $argSign = $this->argSort($parameter);
        $newSign = $this->createLinkstring($argSign);
        $str = $this->rsaSign($newSign, $private_key_path);
        return $str;
    }
  
    public function notifyurl(){
        //计算得出通知验证结果
        $alipay_config = C('alipay_config');    //必须
        $alipayNotify = new \AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代      
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $_POST['out_trade_no'];
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($_POST['trade_status'] == 'TRADE_FINISHED') {
                $this->confirmPayment($out_trade_no,1);
            }else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                $this->confirmPayment($out_trade_no,1);
            }
                
            echo "success";     //请不要修改或删除
            logResult("成功，商户订单号".$out_trade_no.",支付宝交易号：".$trade_no.",交易状态：".$trade_status);
        }
        else {
            //验证失败
            echo "fail";
            logResult("失败");
//          logResult("");
            
            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }

    function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        
        return $arg;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * RSA签名
     * @param $data 待签名数据
     * @param $private_key_path 商户私钥文件路径
     * return 签名结果
     */
    function rsaSign($data, $private_key_path) {
        $priKey = file_get_contents($private_key_path);
        $res = openssl_get_privatekey($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 确认支付&&支付成功
    */
    public function confirmPayment($order_number,$total_fee){
        $orderMod = M('order');
        $trade_order_number = explode("_",$order_number);
        $where['order_number'] = $trade_order_number[0];
        $orderInfo = $orderMod->where($where)->find();
        $orderInfo['status'] = 5;
        $res = $orderMod->where($where)->save($orderInfo);
        if($res){
            $trackMod = M('order_track');
            $trackData['log_time'] = date('Y-m-d H:i:s',time());
            $trackData['log_order_number'] = $orderInfo['order_number'];
            $trackData['log_order_id'] = $orderInfo['id'];
            $trackData['log_user_id'] = $orderInfo['userid'];
            $trackData['log_order_status'] = $orderInfo['status'];
            $trackData['log_desc'] = 'APP 付款';
            $trackData['log_quote'] = $total_fee;
            $trackRes = $trackMod->add($trackData);
        }
    }

}