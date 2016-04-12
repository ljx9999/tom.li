<?php
namespace Home\Controller;
use Home\Model\UserModel;
use Home\Model\WorkerModel;
use Home\Model\TaskModel;
use Home\Model\OrderModel;
use Home\Model\WechatMessageModel;
use Home\Model\SmsModel;
use Think\Controller;
class UserController extends FrontBaseController {
    /*会员中心*/
    public function index(){
        $this->display();
    }

    /*个人资料*/
    public function userInfo(){
        $info = UserModel::userInfoModel();
        $this->assign('info',$info['info']);
        $this->display();
    }


    /*获取个人资料*/
    public function getUserInfo(){
        $uid = I('uid');
        $id = $uid?$uid:$_SESSION['uid'];
        $User = M("User");
        $where['userid'] = $id;
        $info = $User->where($where)->find();
        $this->ajaxReturn($info);
    }

    /*提交资料*/
    public function editAct(){
        $uid = I('uid');
        $id = $uid?$uid:$_SESSION['uid'];
        $data['realname'] = I('realname');
        $data['nickname'] = I('nickname');
        $data['mobile'] = I('mobile');
        $data['email'] = I('email');
        $data['town'] = I('city');
        $data['town_add'] = I('town');
        $data['prov'] = I('prov');
        $result = UserModel::edit($data,$id);
        $this->ajaxReturn($result);
    }
    /*收藏列表*/
    public function collection(){
        $result = UserModel::getCollectionList($_SESSION['uid']);
        if($result){
            $this->assign("list",$result);
        }else{
            $this->assign("list","暂无收藏信息");
        }
        $this->display();
    }

     /*新收藏列表*/
    public function ajaxCollection(){
        $uid = I('uid');
        $result = UserModel::getCollectionList($uid);
        if($result){
            $data['status'] = 1;
            $data['result'] = $result;
            $this->ajaxReturn($data);
        }else{
            $data['status'] = 0;
            $data['msg'] = '暂无收藏信息' ;
            $this->ajaxReturn($data);
        }
    }




    /*竞标管理*/
    public function review_home(){
        
        $this->display();
    }

    /*获取竞标管理内容*/
    public function getReviewInfo(){
        $uid = I('uid');
        $id = $uid?$uid:$_SESSION['uid'];
        $data['uid'] = $id;
        $data['status'] = array('gt',-1);
        $result = TaskModel::getReviewList($data);
        $this->ajaxReturn($result,'JSON');    
    }

    
    /*提交订单*/
    public function ajaxSubOrder(){
        $uid = I('uid');
        $userid = $uid?$uid:$_SESSION['uid'];
        $id = I('id');
        $feedBack = M('feedback');
        $feeds = $feedBack->where('id='.$id)->find();
        $demand = M('demand');
        $where['id'] =  $feeds['programid'];
        $list = $demand->where($where)->find();
        $data['userid'] = $userid;
        $data['worker_id'] = $feeds['fid'];
        $data['demand_id'] = $feeds['programid'];
        $data['quote'] = $feeds['quote'];
        $data['feedback_id'] = $id;
        $order_number = date('Ymd',time()).mt_rand(10000000,99999999);
        $data['order_number'] = $order_number;
        $data['status'] = 1;
        $data['inputtime'] = time();
        $order = M('order');
        $res = $order->data($data)->add();
        if($res>0){
            $wheres['id'] = $feeds['programid'];
            $datas['status'] = 1;
            $demand->where($wheres)->save($datas);
            $map['programid'] = $feeds['programid'];
            $shuju['status'] = 0;
            $feedBack->where($map)->save($shuju);
            // $this->smsCode($feeds['worker_mobile']);
            $foreman = M('foreman');
            $tem_id = "ZWqXD3NCC8nDFzWdpZFpjtlO8EJDz45h1RNB3bLQ4hg";
            $foremanInfo = $foreman->where('id='.$feeds['fid'])->find();
            $data = array(
                'first'=>array('value'=>urlencode("您好,您有一个新订单需要处理，请查看"),'color'=>"#743A3A"),
                'keyword1'=>array('value'=>urlencode($order_number)),
                'keyword2'=>array('value'=>urlencode("装修订单")),
                'keyword3'=>array('value'=>urlencode("待处理")),
                'remark'=>array('value'=>urlencode('更具体的订单详情，请查看详情，或进入个人中心查看'),'color'=>'#FFFFFF'),
            );
            $url ="http://www.didifree.com/didifree/Order/orderInfo_gz?openid=".$foremanInfo['openid'];
            WechatMessageModel::wechatInfo($foremanInfo['openid'],$data,$tem_id,$url);
            $message = '你的投标已经被选中';
            SmsModel::send_message($message,$foremanInfo['tag']);
            $new_data['status'] = 1;
            $new_data['info'] = '下单成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '下单失败！' ;
            $this->ajaxReturn($new_data);
        }
    }

    /*取消收藏*/
    public function deleteAct(){
        $uid = I('uid');
        $userid = $uid?$uid:$_SESSION['uid'];
        $id = I('id');
        $collect = M('collection');
        $where['id'] = $id;
        $where['uid'] = $userid;
        $res = $collect->where($where)->find();
        if($res){
            $rell = $collect->where($where)->delete();
            if($rell){
                $data['status'] = 1;
                $data['info'] = "取消成功！";
                $this->ajaxReturn($data,'JSON');
            }else{
                $data['status'] = 0;
                $data['info'] = "取消失败！";
                $this->ajaxReturn($data,'JSON');
            }
        }else{
            $data['status'] = 0;
            $data['info'] = "取消失败！";
            $this->ajaxReturn($data,'JSON');        
        }
    }

      /*短信验证码*/
    public function smsCode($mobile) {
        $result = SmsModel::sendSmsDemand($mobile);
        return $result;
    }

      /*短信验证码*/
    public function sendCode() {
        $mobile = I('mobile');
        $result = SmsModel::sendSmsDemand($mobile);
        return $result;
    }

      /*获取我的招标信息*/
    public function myDemand(){
        $uid = $_SESSION['uid'];
        $demand = M('demand');
        $foreman = M('foreman');
        $where['userid'] = $uid;
        $where['status'] = array('neq',-1);
        $list = $demand->where($where)->order('id desc')->limit(50)->select();
        foreach ($list as $key => $val) {
            $worker_name = $foreman->where('id='.$val['fid'])->getField('realname');
            $list[$key]['worker_name'] = $worker_name;
        }
        $this->assign('list',$list);
        $this->display();
    }

    public function getMyDemand(){
        $id = I('uid');
        $uid = $id?$id:$_SESSION['uid'];
        $demand = M('demand');
        $foreman = M('foreman');
        $where['userid'] = $uid;
        $where['status'] = array('neq',-1);
        $list = $demand->where($where)->order('id desc')->limit(50)->select();
        foreach ($list as $key => $val) {
            $worker_name = $foreman->where('id='.$val['fid'])->getField('realname');
            $list[$key]['worker_name'] = $worker_name;
        }
       
        $this->ajaxReturn($list,'JSON'); 
        
    }

    /*撤消投标*/
    public function ajaxDelDemand(){
        $id = I('id');
        $demand = M('demand');
        $where['id'] = $id;
        $data['status'] = -1;
        $res = $demand->where($where)->save($data);
        if($res){
            $data['status'] = 1;
            $data['info'] = "删除成功！";
            $this->ajaxReturn($data,'JSON');
        }else{
            $data['status'] = 0;
            $data['info'] = "删除失败！";
            $this->ajaxReturn($data,'JSON');
        }
    }

    /*修改需求*/
    public function editDemand(){
        $id = I('id');
        $demand = M('demand');
        $where['id'] = $id;
        $list = $demand->where($where)->find();
        $list['needAll'] = str_replace("'","",$list['needAll']);
      
        $type_list = C('HOUSE_TYPE');
        $this->assign('type_list',$type_list);
        $this->assign('list',$list);
        $this->display();
    }

    public function ajaxEditDemand(){
        $id = I('id');
        $demand = M('demand');
        $where['id'] = $id;
        $list = $demand->where($where)->find();
        $list['needAll'] = str_replace("'","",$list['needAll']);
        $type_list = C('HOUSE_TYPE');
        $data['type_list'] = $type_list;
        $data['list'] = $list;
        $this->ajaxReturn($data,'JSON');
        
    }

    /*修改需求动作*/
    public function editDemandAct(){
        $id = I('uid');
        $uid = $id?$id:$_SESSION['uid'];
        $checks = I('CheckAll');
        $old_needAll = I('old_needAll');
        $data['needAll'] = $checks?$checks:$old_needAll;
        $data['community_name'] =I('community_name');
        $data['userName'] =I('userName');
        $data['budget'] =I('budget');
        $data['mobile'] =I('mobile');
        $data['msg'] =I('msg');
        $data['area'] =I('area');
        $data['house_type'] =I('house_type');
        $data['work_time'] =I('work_time');
        $data['userid'] = $uid;
        $data['add_time'] = time();
        $where['id'] = I('demand_id');
        $demand = M('demand');
        $result = $demand->where($where)->save($data); 
        if($result){
            $foreman = M('foreman');
            $res = $foreman->where('`identity` = 2 and `status` = 10 and `type` in  ('.$checkAll.')')->order('level desc')->limit(3)->select();
            foreach ($res as $k => $v) {
                $this->smsCode($v['mobile']);
            }
            $rell = $foreman->where('`identity` = 0 and `status` = 10 ')->order('level desc')->limit(3)->select();
            foreach ($rell as $key => $val) {
                $this->smsCode($val['mobile']);
            }
            $data['status'] = 1;
            $data['info'] = "修改成功！";
            $this->ajaxReturn($data,'JSON');
            
        }else{
            $data['status'] = 0;
            $data['info'] = "修改失败！";
            $this->ajaxReturn($data,'JSON');
        }
       

    }

    public function ajaxDelFeed(){
        $id = I('id');
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $wheres['id'] = $id;
        $wheres['uid'] = $uid;
        $feedBack = M('feedback');
        $feeds = $feedBack->where($wheres)->find();
        if($feeds){
            $data['status'] = -1;
            $res=$feedBack->where('id='.$id)->save($data);
            if($res){
                $data['status'] = 1;
                $data['info'] = "删除成功！";
                $this->ajaxReturn($data,'JSON');
            }else{
                $data['status'] = 0;
                $data['info'] = "删除失败！";
                $this->ajaxReturn($data,'JSON');
            }
        }
    }

    public function myHistory(){
        $uid = $_SESSION['uid'];
        $history = M('history');
        $where['uid'] = $uid;
        $where['status'] = 1;
        $list = $history->where($where)->order('id desc')->limit(30)->select();
        $result = array();
        foreach ($list as $key => $val) {
            $workerContent = WorkerModel::getWorkerContent(json_encode($val['fid']));
            $workerContent = json_decode($workerContent,true);
            $workerContent['add_time'] = $val['add_time'];
            $result[] = $workerContent; 
        }
        $this->assign('result',$result);
        $this->display();
    }

    public function ajaxMyHistory(){
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $history = M('history');
        $where['uid'] = $uid;
        $where['status'] = 1;
        $list = $history->where($where)->order('id desc')->limit(30)->select();
        $result = array();
        foreach ($list as $key => $val) {
            $workerContent = WorkerModel::getWorkerContent(json_encode($val['fid']));
            $workerContent = json_decode($workerContent,true);
            $workerContent['add_time'] = $val['add_time'];
            $result[] = $workerContent; 
        }
        $this->ajaxReturn($result,'JSON');
        
    }

    //分享成功 加一积分
    public function ajaxAddInt(){
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $user = M('user');
        $where['userid'] = $uid;
        $res= $user->where($where)->setInc('score');
        if($res){
            $datas['status'] = 1;
            $datas['info'] = "分享成功！";
            $this->ajaxReturn($datas,'JSON');
        }else{
            $datas['status'] = 0;
            $datas['info'] = "分享失败！";
            $this->ajaxReturn($datas,'JSON');
        }
        
    }

    //签到加积分
    public function signAddInt(){
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $user = M('user');
        $where['userid'] = $uid;
        $sign_time = $user->where($where)->getField('sign_time');
        if($sign_time + 24*3600 < time() || $sign_time == 0 ){
            $res= $user->where($where)->setInc('score');
            $map['sign_time'] = time();
            $up_res = $user->where($where)->save($map);
            if($res){
                $datas['status'] = 1;
                $datas['info'] = "签到成功！";
                $this->ajaxReturn($datas,'JSON');
            }else{
                $datas['status'] = 0;
                $datas['info'] = "签到失败！";
                $this->ajaxReturn($datas,'JSON');
            }

        }else{
            $datas['status'] = 0;
            $datas['info'] = "一天只能签到一次！";
            $this->ajaxReturn($datas,'JSON');
        }
        
    }

  
    
}
