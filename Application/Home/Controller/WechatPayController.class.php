<?php
/**
 * Created by PhpStorm.
 * User: mackcyl
 * Date: 15/1/20
 * Time: 上午9:09
 */
namespace Home\Controller;
use Home\Model\WechatModel;
use Think\Controller;
use Home\Common\Library\WechatPay;
use Think\Log;
class WechatPayController extends FrontBaseController {

  
    protected $_payLogId = 0;

    function __construct() {
        parent::__construct();
    }


    public function weixinJsPay(){
        $user_id =$_SESSION['uid'];
        $user = M('user');
        $userInfo = $user->where('userid='.$user_id)->find();
        $openId = $userInfo['openid'];
        //$openId = 'owZuzuBa6kPnWLijU3GSeJyq-a6s';//$_SESSION['accessToken']['openid'];
        $orderId = I('orderId');
        $orderModel =M('order');
        $orderInfo = $orderModel->where('id='.$orderId)->find();
        $this->checkPayLog($orderInfo['order_number'],$user_id);
        $unifiedOrder = new WechatPay\UnifiedOrderApi();
        $unifiedOrder->setParameter("openid",$openId);//商品描述
        $unifiedOrder->setParameter("body",$this->getOrderDesc($orderInfo));//商品描述
        //订单号
        $salt = rand(10000,99999);
        $out_trade_no = $orderInfo['order_number']."_".$salt;
        $total_fee = ($orderInfo['quote'] * 0.015);
        $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号

        $unifiedOrder->setParameter("total_fee",floatval($total_fee * 100));//总金额
        $unifiedOrder->setParameter("notify_url",'http://www.didifree.com/didifree/Home/WechatPay/wxpayNotify');//通知地址

        $unifiedOrder->setParameter("attach","$user_id");//附加数据
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型

        $prepay_id = $unifiedOrder->getPrepayId();
        $jsPayApi = new WechatPay\WechatJsApi();
        $jsPayApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsPayApi->getParameters();

        $this->assign("jsApiParameters",$jsApiParameters);
        $this->assign("orderInfo",$orderInfo);
        $this->assign("total_fee",$total_fee);

        $this->display('wxpay_js_api');

    }

    public function memberJsPay(){
        $fid =$_SESSION['fid'];
        $foreman = M('foreman');
        $userInfo = $foreman->where('id='.$fid)->find();
        $openId = $userInfo['openid'];
        $order_number = 'M'.date('Ymd',time()).mt_rand(10000000,99999999);
        $member = M('member_order');
        $data['order_number'] =  $order_number;
        $data['fid'] = $fid;
        $data['quote'] = 10;
        $data['status'] = 0;
        $data['add_time'] = time();
        $data['type'] = 1;
        $res = $member->add($data);    
        $this->checkPayLog($order_number,$fid);
        $unifiedOrder = new WechatPay\UnifiedOrderApi();
        $unifiedOrder->setParameter("openid",$openId);//商品描述
        $unifiedOrder->setParameter("body","会员服务");//商品描述
        //订单号
        $out_trade_no = $order_number;
        
        $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号

        $unifiedOrder->setParameter("total_fee",floatval(0.01 * 100));//总金额
        $unifiedOrder->setParameter("notify_url",'http://www.didifree.com/didifree/Home/WechatPay/memberNotify');//通知地址

        $unifiedOrder->setParameter("attach","$fid");//附加数据
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型

        $prepay_id = $unifiedOrder->getPrepayId();
        //dump($prepay_id);exit();
        $jsPayApi = new WechatPay\WechatJsApi();
        $jsPayApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsPayApi->getParameters();

        $this->assign("jsApiParameters",$jsApiParameters);
        $this->assign("order_number",$order_number);
        $this->assign("total_fee",$total_fee);

        $this->display();

    }




    public function wxpayNotify(){

        $postDateXmlStr  = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postDateArray = $this->xmlStr2Array($postDateXmlStr);
        $postDataLog = LOG_PATH."wechatPay_Notify_PostData".date('y_m_d').".log";

        Log::write("wechatNotifyPost:".serialize($postDateArray),Log::INFO ,'',$postDataLog);

        $order_number = isset($postDateArray['out_trade_no'])? $postDateArray['out_trade_no']:''; //订单号
        $userId = isset( $postDateArray['attach'])? $postDateArray['attach']:'';                   //操作人

        $this->logger($postDateXmlStr ,$order_number);

        $pay_notify_data = serialize($postDateArray);
        $payStatus = $this->updateOrderByMenber($order_number,$userId,$pay_notify_data);

        if($payStatus){
            echo 'success';
            return ;
        }else{
            echo 'fail'; 
        }
        
        return;
    }
    /*会员费回调*/
    public function memberNotify(){

        $postDateXmlStr  = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postDateArray = $this->xmlStr2Array($postDateXmlStr);
        $postDataLog = LOG_PATH."memberPay_Notify_PostData".date('y_m_d').".log";

        Log::write("memberNotifyPost:".serialize($postDateArray),Log::INFO ,'',$postDataLog);

        $order_number = isset($postDateArray['out_trade_no'])? $postDateArray['out_trade_no']:''; //订单号
        $userId = isset( $postDateArray['attach'])? $postDateArray['attach']:'';                   //操作人

        $this->memberLog($postDateXmlStr ,$order_number);

        $pay_notify_data = serialize($postDateArray);
        $payStatus = $this->updateMemberOrder($order_number,$userId,$pay_notify_data);
        if($payStatus){
            echo 'success';
            return ;
        }else{
            echo 'fail'; 
        }
        
        return;
    }




    private function xmlStr2Array($xmlStr){
        $foo = simplexml_load_string($xmlStr);
        $arrayObj = '';

        foreach($foo as $key=>$value){
            $arrayObj[$key] =  (string) $foo->$key;
        }
        return $arrayObj;
    }

    //日志记录
    private  function logger($log_content,$order_number){
        $max_size = 100000;
        $log_filename = LOG_PATH."wechatPay_Notify_PostData".date('y_m_d').".xml";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        $log_content = "<Order_$order_number>".$log_content."</Order_$order_number>";
        file_put_contents($log_filename,$log_content,FILE_APPEND);
    }

    //会员费记录
    private  function memberLog($log_content,$order_number){
        $max_size = 100000;
        $log_filename = LOG_PATH."memberPay_Notify_PostData".date('y_m_d').".xml";
        if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
        $log_content = "<Order_$order_number>".$log_content."</Order_$order_number>";
        file_put_contents($log_filename,$log_content,FILE_APPEND);
    }

    private function getPaymentConfig($payment_id){
        $payMod = M('payment');
        $payObj = $payMod->where('id='.$payment_id)->find();

        $configObj = unserialize($payObj['partner_attr']);
        $config = array();
        foreach($configObj as $cofObj){
            $config[$cofObj['key']] = $cofObj['value'];
        }

        $payObj['config'] = $config;
        return $payObj;
    }

    private function getOrderInfo($order_menber){
        $orderMod = M("dd_order");
        $orderInfo = $orderMod->where("order_number='".$order_menber."'")->find();
        return $orderInfo;
    }

    private function getOrderDesc($orderInfo){

        return '装修款项';
    }

    private function checkPayLog($order_id,$user_id){

        $where['order_id'] = $order_id;
        $where['user_id'] = $user_id;
        $payLogMod = M("pay_log");
        $logInfo = $payLogMod->where($where)->find();

        if(!empty($logInfo)){
            if($logInfo['pay_status'] > 0){
                $this->payError(4000);
            }
            $this->_payLogId = $logInfo['id'];
        }
        return true;
    }


    private function savePayLog($orderObj,$payStatus=0,$pay_notify_data){
        $logObj = array();
        $logObj['order_id'] = $orderObj['order_number'];
        $logObj['add_time'] = time();
        $logObj['pay_status'] = $payStatus;
        $logObj['pay_notify_data'] = $pay_notify_data;

        $payLogMod = M("pay_log");
        $this->checkPayLog($logObj['order_id'],$orderObj['userid']);


        if($this->_payLogId > 0){
            $logObj['id'] = $this->_payLogId;

            $payLogMod->save($logObj);
        }else{
            $payLogMod->add($logObj);
        }

    }

    private function updateOrderByMenber($order_menber,$userId,$pay_notify_data){
        try{
            $orderMod = M('order');
            $orderInfo = $orderMod->where("order_number='".$order_menber."'")->find();
            if(empty($orderInfo)){
                return 0;
            }else{
                $status = 5;
                $this->savePayLog($orderInfo,$status,$pay_notify_data);
                return 1;
            }
            return 1;
        }catch (Exception $e){
            $destination = LOG_PATH."wechatPay_".date('y_m_d').".log";
            Log::write($e->getMessage(),Log::ERR ,'',$destination);
            return 0;
        }


    }
    //更新会员缴费状态
    private function updateMemberOrder($order_menber,$userId,$pay_notify_data){
        try{
            $orderMod = M('member_order');
            $orderInfo = $orderMod->where("order_number='".$order_menber."'")->find();
            if(empty($orderInfo)){
                return 0;
            }else{
                if($orderInfo['status'] == 0){
                   $data['status'] = 1;
                   $res = $orderMod->where("order_number='".$order_menber."'")->save($data);
                }else if($orderInfo['status'] == 1){
                    return 0;
                }
                return 1;
            }
            return 1;
        }catch (Exception $e){
            $destination = LOG_PATH."memberPay_".date('y_m_d').".log";
            Log::write($e->getMessage(),Log::ERR ,'',$destination);
            return 0;
        }


    }
    //微信APP支付接口
    public function appNewPay(){
        $orderId = I('order_id');
        $orderModel =M('order');
        $orderInfo = $orderModel->where('id='.$orderId)->find();
        $this->checkPayLog($orderInfo['order_number'],$user_id);
        
        //订单号
        $salt = rand(10000,99999);
        $out_trade_no = $orderInfo['order_number']."_".$salt;
        $total_fee = floatval($orderInfo['quote'] * 0.015 * 100);
        $orderBody = "滴滴装修款项";
        $trade_no = $out_trade_no;
        //下面开始
        $WxPayHelper = new WechatPay\WxPayHelper();
        $response = $WxPayHelper->getPrePayOrder($orderBody,$trade_no,$total_fee);
        $x = $WxPayHelper->getOrder($response['prepay_id']);
        $this->ajaxReturn($x);
    }

    //微信APP支付接口
    public function appIosPay(){
        $orderId = I('order_id');
        $orderModel =M('order');
        $orderInfo = $orderModel->where('id='.$orderId)->find();
        $this->checkPayLog($orderInfo['order_number'],$user_id);
        
        //订单号
        $salt = rand(10000,99999);
        $out_trade_no = $orderInfo['order_number']."_".$salt;
        $total_fee = floatval($orderInfo['quote'] * 0.015 * 100);
        $orderBody = "滴滴装修款项";
        $trade_no = $out_trade_no;
        //下面开始
        $WxPayHelper = new WechatPay\WxPayConf();
        $response = $WxPayHelper->getPrePayOrder($orderBody,$trade_no,$total_fee);
        $x = $WxPayHelper->getOrder($response['prepay_id']);
        $this->ajaxReturn($x);
    }


    /*
    微信支付后回调页面
    */
    public function wx_notify_url_new() {
        $return_info             = file_get_contents('php://input','r');
        if(!$return_info)
            echo $this->_wxReturnDataFormat(array('code' => 'FILE','mes' => '无返回值'));
        //log目录
        $wx_log_path             = LOG_PATH . '/Wx_log/';
        if(!is_dir($wx_log_path))
            mkdir($wx_log_path,0777,true);
        $wx_log_dir              = $wx_log_path .date('Y-m-d'). '.log';
        Log::write('服务器获取到的总参数：' . $return_info, Log::DEBUG, '', $wx_log_dir);
        //匹配return_code
        $return_code             = $this->_wxGetOneInfo('return_code',$return_info);
        if($return_code == 'SUCCESS'){
            //获取订单号
            $order_index         = $this->_wxGetOneInfo('out_trade_no',$return_info);
            $total_fee         = $this->_wxGetOneInfo('total_fee',$return_info);
            $this->confirmPayment($order_index,$total_fee);
            //业务处理。。。。
            echo $this->_wxReturnDataFormat(array('code' => 'SUCCESS','mes' => 'OK'));
        }else {
            //暂放
            echo $this->_wxReturnDataFormat(array('code' => 'FILE','mes' => 'code错误'));
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

    /*
        匹配某一项内容
        @param         pat     要匹配的内容正则表达式
                    info     内容
    */
    private function _wxGetOneInfo($pat,$info) {
        if($pat == 'total_fee')
            $pattern                 = '/<total_fee>([\d]+)<\/total_fee>/iU';
        else
            $pattern                 = '/<'.$pat.'><!\[CDATA\[([\s\S]+)\]\]><\/'.$pat.'>/uiU';
        $mat_int                     = preg_match_all($pattern, $info, $matches);
        if(!$mat_int)
            return '匹配异常';
        return $matches[1][0];
    }
    /*
        拼接微信返回值信息
    */
    private function _wxReturnDataFormat($arr) {
        $str             = '<xml>';
        $str             .= '<return_code><![CDATA['.$arr['code'].']]></return_code>';
        $str             .= '<return_msg><![CDATA['.$arr['mes'].']]></return_msg>';
        $str             .= '</xml>';
        return $str;
    }


    
} 

