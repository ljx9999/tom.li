<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\UserModel;
use Home\Model\SmsModel;
use Home\Common\Library\WechatPay;
class WxActController extends FrontBaseController {
    function __construct() {
        parent::__construct();

    }
   public function index(){
     $this->display();
   }

   public function getInfos(){
        $data['mobile']= I("mobile");
        $data['user_name'] = I('userName');
        $data['area'] = I("area");
        $data['type'] = 1;
        $data['add_time'] = time();
        $new_activity = M("new_activity");
        $res = $new_activity->add($data);
        if($res){
          $list['status'] = 1;
          $list['info'] = "预约成功！";
        }else{
          $list['status'] = 0;
          $list['info'] = "预约失败！";
        }
        $this->ajaxReturn($list);
   }

  

    

}