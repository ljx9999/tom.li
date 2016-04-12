<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\OrderModel;
use Home\Model\SmsModel;
use Home\Model\WechatMessageModel;
class OrderController extends FrontBaseController {
            function __construct() {
                parent::__construct();

            }
            /*招标页面*/
            public function index(){
                $uid = $_SESSION['uid'];
                $User = M('user');
                $mobile = $User->where('userid ='.$uid)->getField('mobile');
                $realname = $User->where('userid ='.$uid)->getField('realname');
                $this->assign('mobile',$mobile);
                $this->assign('userName',$realname);
                $this->display();
            }

            /*招标获取用户名和手机*/
            public function getUserInfo(){
                $uid = I('uid');
                $User = M('user');
                $mobile = $User->where('userid ='.$uid)->getField('mobile');
                $realname = $User->where('userid ='.$uid)->getField('realname');
                $data['mobile'] = $mobile;
                $data['userName'] = $realname;
                $this->ajaxReturn($data);
            }

            /*提交需求详情*/
            public function demand(){
                $uid = I('uid');
                $id = $uid?$uid:$_SESSION["uid"];
                $user = M('user');
                $wheres['userid'] = $id;
                $my_city = $user->where($wheres)->getField('city');
                $checks = I('CheckAll');
                $need_arr = explode(',',$checks);
                $data['community_name'] =I('community_name');
                $data['userName'] =I('userName');
                $data['budget'] =I('budget');
                $data['mobile'] =I('mobile');
                $data['msg'] =I('msg');
                $data['fid'] =I('fid');
                $data['area'] =I('area');
                $data['house_type'] =I('house_type');
                $data['work_time'] =I('work_time');
                $data['userid'] = $id;
                $data['add_time'] = time();
                $address = I('address'); 
                if($address){
                    $data['city'] = $address['city'];
                    $data['province'] = $address['prov'];
                    $data['town'] = $address['dist'];
                }else{
                    $data['city'] = I('city')?I('city'):$my_city;
                    $data['province'] = I('prov');
                    $data['town'] = I('dist');
                }
                foreach ($need_arr as $key => $val) {
                    $data['needAll'] = $val;
                    $result = OrderModel::addDemandInfo(json_encode($data));
                    $types = str_replace("'","",$val); 
                    if($result['status'] == 1){
                        $foreman = M('foreman');
                        $res = $foreman->where('`identity` = 2 and `status` = 10 and `type` like "%'.$types.'%" ')->order('level desc')->limit(3)->select();
                        foreach ($res as $k => $v) {
                            $this->smsCode($v['mobile']);
                        }
                        $rell = $foreman->where('`identity` = 0 and `status` = 10 ')->order('level desc')->limit(3)->select();
                        foreach ($rell as $key => $val) {
                            $this->smsCode($val['mobile']);
                        }
                    }
                }
                $this->ajaxReturn($result);
            }

            /*用户订单列表页*/
            public function orderInfo(){
                $data['id'] = $_SESSION['uid'];
                $data['type'] = 'user';
                $info = OrderModel::checkOrderInfo($data);
                $this->assign('info',$info);

                $this->display();
            }


            /*用户订单列表页*/
            public function ajaxOrderInfo(){
                $uid = I('uid');
                $id = $uid?$uid:$_SESSION["uid"];
                $data['id'] = $id;
                $data['type'] = 'user';
                $info = OrderModel::checkOrderInfo($data);
                $this->ajaxReturn($info);
               
                
            }


            /*工长订单列表页*/
            public function orderInfo_gz(){
                $data['id'] = $_SESSION['fid'];
                $data['type'] = 'foreman';
                $info = OrderModel::checkOrderInfo($data);
                $this->assign('info',$info);
                $this->display();
            }

            /*工长订单列表页*/
            public function ajaxForemanInfo(){
                $fid = I('fid');
                $id = $fid?$fid:$_SESSION["fid"];
                $data['id'] = $id;
                $data['type'] = 'foreman';
                $info = OrderModel::checkOrderInfo($data);
               
                $this->ajaxReturn($info);
            }

            public function detail(){
                $id = I('id');
                $order = M("Order");
                $orderInfo = $order->where("id =".$id)->find();             
                $demandInfo = M('demand')->where("id = ".$orderInfo['demand_id'])->find();
                $evaluate = M('evaluate')->where("order_id = ".$id)->find();
                $this->assign('orderInfo',$orderInfo);
                $this->assign('demandInfo',$demandInfo);
                $this->assign('evaluate',$evaluate);
                $this->display();
            }

            public function ajaxGetDetail(){
                $id = I('id');
                $order = M("Order");
                $orderInfo = $order->where("id =".$id)->find();             
                $demandInfo = M('demand')->where("id = ".$orderInfo['demand_id'])->find();
                $evaluate = M('evaluate')->where("order_id = ".$id)->find();
                $data['orderInfo'] = $orderInfo;
                $data['demandInfo'] = $demandInfo;
                $data['evaluate'] = $evaluate;
                $this->ajaxReturn($data);
            }
            
            /* 订单报价*/
            public function orderQuoto(){
                $data['id'] = I('id');
                $data['quote'] =I('quote');
                $data['status'] =1;
                $info = OrderModel::updateOrderInfo($data);
                $data['info'] = $info;
                $this->ajaxReturn($data);
            }

            /**
             * 确认支付&&支付成功
             */
            public function confirmPayment(){
                $orderId = I('orderId');
                $orderMod = M('order');
                $orderInfo = $orderMod->find($orderId);
                $orderInfo['status'] = 5; 
                $res = $orderMod->where('id='.$orderId)->save($orderInfo);
                if($res){
                    $trackMod = M('order_track');
                    $trackData['log_time'] = date('Y-m-d H:i:s',time());
                    $trackData['log_order_number'] = $orderInfo['order_number'];
                    $trackData['log_order_id'] = $orderId;
                    $trackData['log_user_id'] = $orderInfo['userid'];
                    $trackData['log_order_status'] = $orderInfo['status'];
                    $foreman = M('foreman');
                    $openid = $foreman->where('id='.$orderInfo['worker_id'])->getField('openid');
                    $tem_id = "ZWqXD3NCC8nDFzWdpZFpjtlO8EJDz45h1RNB3bLQ4hg";
                    
                    $trackData['log_desc'] = '系统确认已支付定金!';
                    $data = array(
                        'first'=>array('value'=>urlencode("您好,您有一个订单更新需要处理，请查看"),'color'=>"#743A3A"),
                        'keyword1'=>array('value'=>urlencode($orderInfo['order_number'])),
                        'keyword2'=>array('value'=>urlencode("装修订单")),
                        'keyword3'=>array('value'=>urlencode("已付定金")),
                        'remark'=>array('value'=>urlencode('更具体的订单详情，请查看详情，或进入个人中心查看'),'color'=>'#FFFFFF'),
                    );
                    $url ="http://www.didifree.com/didifree/Order/orderInfo_gz?openid=".$openid;
                    $trackData['log_quote'] = ($orderInfo['quote'] * 0.015);
                    WechatMessageModel::wechatInfo($openid,$data,$tem_id,$url);
                    $trackRes = $trackMod->add($trackData);
                    if($trackRes){
                        $this->success('支付成功！');
                    }else{
                        $this->error('支付失败！');
                    }  
                }else{
                    $this->error('支付失败！');
                }
            }

    /**
     * 会员费确认支付&&支付成功
     */
    public function havePayed(){
        $order_number = I('order_number');
        $order_member = M('member_order');
        $orderInfo = $order_member->where("order_number='".$order_number."'")->find();
        if($orderInfo['status'] == 0){
            $data['status'] = 1;
            $res = $order_member->where("order_number='".$order_number."'")->save($data);
        }
        $foreman = M('foreman');
        $where['id'] = $_SESSION['fid'];
        $datas['is_member'] = 1;
        $datas['member_add_time'] = time();
        $rell = $foreman->where($where)->save($datas);
        $trackMod = M('order_track');
        $trackData['log_time'] = date('Y-m-d H:i:s',time());
        $trackData['log_order_number'] = I('order_number');
        $trackData['log_user_id'] = $_SESSION['fid'];
        $trackData['log_order_status'] = 1;
        $trackData['log_quote'] = 10;
        $trackData['log_desc'] = '系统确认已支付会员费!'; 
        $trackRes = $trackMod->add($trackData);
        if($trackRes){
            $this->success('支付成功！');
        }else{
            $this->error('支付失败！');
        }    
    }
        


     /**
     * 支付结果
     */
    public function payed(){
        $orderId = I('orderId');
        $orderMod = M('order');
        $orderInfo = $orderMod->find($orderId);
        $total_fee = ($orderInfo['quote'] * 0.015);
        $this->assign('orderInfo',$orderInfo);
        $this->assign('total_fee',$total_fee);
        $this->display();
    }

     /**
     * 支付结果
     */
    public function memberPayed(){
        $order_number = I('order_number');
        $this->assign('order_number',$order_number);
        $this->assign('total_fee',10);
        $this->display();
    }

    /**
    *已完工，申请验收
    *
    **/
    public function orderDone(){
        $id = I('id');
        $mobile = I('mobile');
        $order = M('order');
        $orderInfo = $order->where('id='.$id)->find();
        $user = M('user');
        $userInfo = $user->where('userid='.$orderInfo['userid'])->find();
        $where['id'] = $id;
        $data['status'] = 15;
        $res = $order->where($where)->save($data);
        if($res){
            // $this->smsCode($mobile);
            $tem_id = "ZWqXD3NCC8nDFzWdpZFpjtlO8EJDz45h1RNB3bLQ4hg";
            $data = array(
                'first'=>array('value'=>urlencode("您好,您有一个订单更新需要处理，请查看"),'color'=>"#743A3A"),
                'keyword1'=>array('value'=>urlencode($orderInfo['order_number'])),
                'keyword2'=>array('value'=>urlencode("装修订单")),
                'keyword3'=>array('value'=>urlencode("已完工，申请验收")),
                'remark'=>array('value'=>urlencode('更具体的订单详情，请查看详情，或进入个人中心查看'),'color'=>'#FFFFFF'),
            );
            $url ="http://www.didifree.com/didifree/Order/orderInfo?openid=".$userInfo['openid'];
            WechatMessageModel::wechatInfo($userInfo['openid'],$data,$tem_id,$url);
            $message = '已完工，申请验收中';
            SmsModel::send_message($message,$userInfo['tag']);
            $new_data['status'] = 1;
            $new_data['info'] = '更新成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '更新失败！' ;
            $this->ajaxReturn($new_data);
        }
    }

    /**
    *
    *评价订单操作
    *
    **/
    public function evaluate(){
        $uid = I('uid');
        $id = $uid?$uid:$_SESSION["uid"];
        $data['order_id'] = I('order_id');
        $data['user_id'] = $id;
        $data['foreman_id'] = I('worker_id');
        $data['message']= I('message');
        $data['status'] =1;
        $data['score'] =I('score');
        $data['fw_score'] =I('new_score');
        $data['add_time'] = time();
        $evaluate = M('evaluate');
        $res = $evaluate->add($data);
        if($res){
            $order = M('order');
            $wheres['id'] = I('order_id');
            $order_number = $order->where($wheres)->getField('order_number');
            $datas['status'] = 25;
            $rell = $order->where($wheres)->save($datas);
            $foreman = M('foreman');
            $openid = $foreman->where('id='.$data['foreman_id'])->getField('openid');
            $data = array(
                'first'=>array('value'=>urlencode("您好,您有一个订单更新需要处理，请查看"),'color'=>"#743A3A"),
                'keyword1'=>array('value'=>urlencode($order_number)),
                'keyword2'=>array('value'=>urlencode("装修订单")),
                'keyword3'=>array('value'=>urlencode("客户已评价")),
                'remark'=>array('value'=>urlencode('更具体的订单详情，请查看详情，或进入个人中心查看'),'color'=>'#FFFFFF'),
            );
            WechatMessageModel::wechatInfo($openid,$data);
            $new_data['status'] = 1;
            $new_data['info'] = '评价成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '评价失败！' ;
            $this->ajaxReturn($new_data);
        }
    }

     /*短信验证码*/
    public function smsCode($mobile) {
        $result = SmsModel::sendSmsDemand($mobile);
        return $result;
    }

     /*推送结果*/
    public function send_message() {
        $message = I('message');
        $result = SmsModel::send_message($message);
        return $result;
    }

    public function orderEva(){
        $order_id = I('orderId');
        $worker_id = I('worker_id');
        $this->assign('order_id',$order_id);
        $this->assign('worker_id',$worker_id);
        $this->display();
    }

    public function cancelOrder(){
        $id = I('id');
        $uid = I('uid');
        $userid = $uid?$uid:$_SESSION["uid"];
        $cancel_info = I('cancel_info');
        $order = M('order');
        $where['id'] = $id;
        $where['userid'] = $userid;
        $res = $order->where($where)->find();
        if($res){
            $cancel = M('cancel_order');
            $data['userid'] = $userid;
            $data['add_time'] = time();
            $data['order_id'] = $id;
            $data['cancel_info'] = $cancel_info;
            $rell = $cancel->add($data);
            if($rell){
                $map['status'] = 30;
                $order->where('id ='.$id)->save($map);
                $new_data['status'] = 1;
                $new_data['info'] = '撤单成功！' ;
                $this->ajaxReturn($new_data);
            }else{
                $new_data['status'] = 0;
                $new_data['info'] = '撤单失败！' ;
                $this->ajaxReturn($new_data);
            }
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '你无权进行此操作！' ;
            $this->ajaxReturn($new_data);
        }

    }


}