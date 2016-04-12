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
        $data['private_key'] = 'MIICdAIBADANBgkqhkiG9w0BAQEFAASCAl4wggJaAgEAAoGBALpzvNq3BXuAc2dryO0XMdXWL/C9J4c+C8Kr5UmoxYoZhEnISkl26Ry/gnQPJ0DdpSsa4vVoMJ6lUhMnsJZyG/X74nH3d1bWMDDsJagyqWN/KrTDGej+CailT2iXM46HgTE2bHN6LlYEgV6/0hGXctkZJWpClTVlAFRDw5+Pqz8lAgMBAAECfy0n2pUdvjYq5cgmUoMZGviu5u1m4ive2vpKpz0voqfPhjpYR6WDJvHPb8ir4tXS2C2YVV8Z3KyPXVFGS34kJKEEjJ3lu+UvhpIgCrGkV53HiTXDlLF1dPKUZzpG1aaN/DEuYyeb41KqV9zzoJ7cZ6CRcdlEEV3oqnj1wfZj6kECQQDbTBYo2VxSf/8WqtIid28q1IHDWrOGqsCSnbC23GoPTJKdlyiyEGe3irxA5ot6fQLQ2CZ89b4WMqLZiG/ONNJJAkEA2ahiOIYPfdI7iKC+mE3KiWKhPovF0ZcI9Fesz5E/xAg+8UeEmoVwJQ06DWkpQgRWZOhyYt7rBFiBaUfk0nwF/QJAYs4tTcphZmp1LGlatFzLoaiNrs83A/37NhQGgt1ci/gcNxTcKR5rHK9NkpBNV6rrkd1Rugue0bGO3iQy+92gwQJBANkNpR1u+0XjAAGJI2hosyB8XgYshtIzBvf78tZvp6JLAtHEG0Wo6iPY49p5024FO06Xy3IzkTSdOjiOPG92xNUCQAul5cG7o+jSt8PGyMv7l9b5awWUKPYA9Mq2/mWzv7O8vcEbaPmc8WaixJL+5PEVLCPfPNXvuwXLrdYYEPBQrTw='; 
        $this->ajaxReturn($data);
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