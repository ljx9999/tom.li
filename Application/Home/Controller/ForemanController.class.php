<?php
namespace Home\Controller;
use Home\Model\ForemanModel;
use Home\Model\UserModel;
use Think\Controller;
use Think\Controller\Login;
class ForemanController extends FrontBaseController {
    /*会员中心*/
    public function index(){
      
        $this->display();
    }

    /*获取工人信息*/
    public function getForemanInfo(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION['fid'];
        $info = ForemanModel::userInfoModel($id);
        if($info['is_member'] == 1){
            if(($info['member_add_time'] + 30 * 86400) - time() > 0){
                $info['members'] = 1;
            }else{
                $info['members'] = 0;
                $foreman = M('foreman');
                $where['id'] = $id;
                $data['is_member'] = 0;
                $foreman->where($where)->save($data);
            }
        }else{
            $info['members'] = 0;
        }
        $cash = $this->haveMoney();
        $shengyu = $info['income'] - $cash; 
        $cash = $cash?$cash:0;
        $info_arr =array("info"=>$info,"shengyu"=>$shengyu,"cash"=>$cash);
        $this->ajaxReturn($info_arr);
    }

    /*获取提现金额*/
    public function haveMoney(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $my_bank = M('cash');
        $where['fid'] = $id;
        $where['bank_status'] = array('egt',0);
        $my_cash = $my_bank->where($where)->select();
        $cash = "";
        foreach ($my_cash as $key => $val) {
            $cash+=$val['income'];
        }
        return $cash;

    }


    /*获取个人资料*/
    public function getUserInfo(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $info = ForemanModel::userInfoModel($id); 
        $this->ajaxReturn($info);
    }
    /*提交资料*/
    public function editAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $data['realname'] = I('realname');
        $data['age'] = I('age');
        $data['work_life'] = I('work_life');
        $data['address'] = I('address');
        $data['content'] = I('content');
        $data['head_image'] = I('images');
        $data['company_name'] = I('company_name');
        $info = ForemanModel::editForeman($data,$id);
        $new_data['image'] = I('images');
        if($info['info']){
            $new_data['status'] = 1;
            $new_data['info'] = '更新成功！' ;
            $new_data['url'] = U('Foreman/index');
            $this->ajaxReturn($new_data);
        }else{
           $new_data['status'] = 0;
           $new_data['info'] = '没有更新的信息！' ;
           $this->ajaxReturn($new_data);
        }

    }

    public function uploadCase(){
        $this->display('case_upload');
    }

    /*添加案例图片*/
    public function addAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $case = M('case_list');
        $data['shequ_name'] = I('shequ_name');
        $data['content'] = I('content');
        $data['add_time'] = time();
        $data['status'] = 1;
        $data['fid'] = $id;
        $data['is_cover'] = $_REQUEST['is_cover'];
        $res = $case->add($data);
        if($res){
            $images = $_REQUEST['images'];
            $image_str = I('image_str');
            if($image_str){
               $imageIds = explode(",", $image_str); 
            }else{
               $imageIds =  join( $images , "," ); 
            }
            $where['id'] = array("in",$imageIds);
            $imageObj['p_id'] = $res;
            $image = M('image');
            $rell = $image->where($where)->save($imageObj);
            $new_data['images'] = $images; 
            if($rell){
                $new_data['status'] = 1;
                $new_data['info'] = '上传成功！' ;
                $this->ajaxReturn($new_data,"JSON");
            }else{
                $new_data['status'] = 0;
                $new_data['info'] = '上传失败！' ;
                $this->ajaxReturn($new_data,"JSON");
            }
        }
        
    }

    /*银行卡管理*/
    public function bankManage(){
        $bank = M("bank_manage"); 
        $where['uid'] = $_SESSION['fid'];
        $where['bank_status'] = 1;
        $res = $bank->where($where)->find();
        if($res){
            $this->assign('banks',$res);
            $this->display('myBank');
        }else{
            $this->display();
        }
        
    }

    public function ajaxGetBankInfo(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $bank = M("bank_manage"); 
        $where['uid'] =$id;
        $where['bank_status'] = 1;
        $res = $bank->where($where)->find();
        if($res){
            $res['status'] = 1;
            $this->ajaxReturn($res,'JSON');
        }else{
            $data['status'] = 0;
            $data['msg'] = "未绑定银行卡";
            $this->ajaxReturn($data,'JSON');
        }
       
    }

     /*银行卡管理*/
    public function addbank(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $bank = M("bank_manage"); 
        $data['uid'] =$id;
        $data['add_time'] = time();
        $data['bank_status'] = 1;
        $data['realName'] = I('realname');
        $data['card_num'] = I('card_num');
        $data['bank_name'] = I('bank_name');
        $data['bank_num'] = I('bank_num');
           
        $result = $bank->add($data); // 写入数据到数据库    
        if($result){
            $new_data['status'] = 1;
            $new_data['info'] = '添加银行卡成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 1;
            $new_data['info'] = '添加银行卡失败！' ;
            $this->ajaxReturn($new_data);
        }
        
     }

     /*删除重置银行卡*/
     public function delBank(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $where['id'] = I('id');
        $where['uid'] = $id;
        $bank = M("bank_manage"); 
        $data['bank_status'] = -1;
        $res = $bank->where($where)->save($data);
        if($res){
            $new_data['status'] = 1;
            $new_data['info'] = '删除成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '删除失败！' ;
            $this->ajaxReturn($new_data);
        }
     }

     /*提现前置条件判断是否绑定银行卡*/
     public function getMoney(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $bank = M('bank_manage');
        $where['uid'] = $id;
        $where['bank_status'] = 1;
        $res = $bank->where($where)->find();
        if($res){
            $new_data['status'] = 1;
            $new_data['info'] = '绑定成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '未绑定银行卡！' ;
            $this->ajaxReturn($new_data);
        }
     }

     /*提现页面*/
     public function getMyCash(){
        
        $this->display();
     }

     /*获取账户信息*/
     public function ajaxGetMyCash(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $bank = M('bank_manage');
        $where['uid'] = $id;
        $where['bank_status'] = 1;
        $res = $bank->where($where)->find();
        $Foreman = M('foreman');
        $infos = $Foreman->where('id='. $id)->find();
        $income= ForemanModel::getUserMoney($id);
        $cash = $this->haveMoney();
        $shengyu = round(($income - $cash),3); 
        if($shengyu < 0){
           $shengyu = 0; 
        }
        $real_income = round($shengyu * (1-0.015),3);
        $infos['income'] = $income;
        $infos['shengyu'] = $shengyu;
        $infos['real_income'] = $real_income;
        $data['infos'] = $infos;
        $data['bankList'] = $res;
        $this->ajaxReturn($data,'JSON');

     }

     /*提现操作*/
     public function drawMoneyAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $mobile = I('mobile');
        $l['mobile']= $mobile;
        $l['code'] = I('sms_code');
        $checkSms = UserModel::checkSmsCode(json_encode($l));
        if($checkSms['status'] != 1){
           $this->ajaxReturn($checkSms);
        }
        $Foreman = M('foreman');
        $infos = $Foreman->where('id='. $id)->find();
        $income= ForemanModel::getUserMoney($id);
        if(I('my_cash') > $income){
           $this->error('提现金额大于账户余额！'); 
        }
        $data['fid'] = $id;
        $data['user_name'] = I('realname');
        $data['card_num'] = I('card_num');
        $data['bank_name'] = I('bank_name');
        $data['mobile'] = $mobile;
        $data['bank_num'] = I('bank_num');
        $data['income'] = I('my_cash');
        $data['add_time'] = time();
        $data['bank_status'] = 0;
        $my_bank = M('cash');
        $res = $my_bank->add($data);
        
        if($res){
            $new_data['status'] = 1;
            $new_data['info'] = '申请成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '申请失败！' ;
            $this->ajaxReturn($new_data);
        }
     }

     /*工人管理*/
     public function workerManage(){
        
        $this->display();
     }

     /*获取工人信息*/
      public function getWorkerInfo(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $worker = M('worker_manage');
        $where['status'] = 1 ;
        $where['fid'] = $id;
        $list = $worker->where($where)->order('id desc')->select();
        foreach ($list as $key => $val) {
           $list[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
        }
        $data['info'] = $list;
        $this->ajaxReturn($data,'JSON');
     }

     /*添加工人页面*/
     public function addWorker(){

        $this->display();

     }
     /*添加工人操作*/
     public function addWorkerAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $worker = M('worker_manage');
        $worker->create();
        $worker->status = 1;
        $worker->add_time = time();
        $worker->fid = $id;
        $res = $worker->add();
        if($res){
            $new_data['status'] = 1;
            $new_data['info'] = '添加成功！' ;
            $this->ajaxReturn($new_data);
        }else{
            $new_data['status'] = 0;
            $new_data['info'] = '添加失败！' ;
            $this->ajaxReturn($new_data);
        }
    }

     /*添加工人操作*/
     public function addNewWorkerAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $worker = M('worker_manage');
        $data['worker_name'] = I('worker_name');
        $data['mobile'] = I('mobile');
        $data['worker_age'] = I('worker_age');
        $data['worker_type'] = I('needAll');
        $data['worker_msg'] = I('msg');
        $data['status'] = 1;
        $data['add_time'] = time();
        $data['fid'] = $id;
        $res = $worker->add($data);
        if($res){
           $rell['status'] = 1;
           $rell['msg'] = "添加成功！";
           $this->ajaxReturn($rell,'JSON');
        }else{
           $rell['status'] = 0;
           $rell['msg'] = "添加失败！";
           $this->ajaxReturn($rell,'JSON');
        }
    }

    /*删除工人*/
    public function deleteWorkers(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $where['id'] = I('id');
        $where['fid'] =  $id;
        $worker = M('worker_manage');
        $data['status'] = -1;
        $res = $worker->where($where)->save($data);
        if($res){
            $rell['status'] = 1;
            $rell['info'] = "删除成功！";
            $this->ajaxReturn($rell,'JSON');
        }else{
            $rell['status'] = 0;
            $rell['info'] = "删除失败！";
            $this->ajaxReturn($rell,'JSON');
        }
    }

    /*客户管理列表*/
    public function customerManage(){
        $this->display();
     }

     /*获取客户信息*/
     public function getCustomerInfo(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $customers = M('customers');
        $where['status'] = 1 ;
        $where['fid'] = $id;
        $list = $customers->where($where)->order('id desc')->select();
        foreach ($list as $key => $val) {
           $list[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
        }
        $data['info'] = $list;
        $this->ajaxReturn($data,'JSON');
     }


     /*添加工人页面*/
     public function addCustomer(){

        $this->display();

     }


      /*添加客户操作*/
    public function addNewCustomerAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $customer = M('customers');
        $data['user_name'] = I('worker_name');
        $data['mobile'] = I('mobile');
        $data['address'] = I('worker_age');
        $data['total_fee'] = I('total_fee');
        $data['protect_time'] = I('protect_time');
        $data['status'] = 1;
        $data['add_time'] = time();
        $data['fid'] = $id;
        $data['msg'] = I('msg');
        $res = $customer->add($data);
        if($res){
           $rell['status'] = 1;
           $rell['msg'] = "添加成功！";
           $this->ajaxReturn($rell,'JSON');
        }else{
           $rell['status'] = 0;
           $rell['msg'] = "添加失败！";
           $this->ajaxReturn($rell,'JSON');
        }
    }

      /*添加客户操作*/
    public function addCustomerAct(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $customer = M('customers');
        $customer->create();
        $customer->status = 1;
        $customer->add_time = time();
        $customer->fid = $id;
        $res = $customer->add();
        if($res){
            $this->success('添加成功！');
        }else{
            $this->error('添加失败！');
        }
    }

     /*删除客户*/
    public function deleteCustomers(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $where['id'] = I('id');
        $where['fid'] = $id;
        $customer = M('customers');
        $data['status'] = -1;
        $res = $customer->where($where)->save($data);
        if($res){
            $rell['status'] = 1;
            $rell['info'] = "删除成功！";
            $this->ajaxReturn($rell,'JSON');
        }else{
            $rell['status'] = 0;
            $rell['info'] = "添加失败！";
            $this->ajaxReturn($rell,'JSON');
        }
    }

     //分享成功 加一积分
    public function ajaxAddInt(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $Foreman = M('foreman');
        $where['id'] = $id;
        $res= $Foreman->where($where)->setInc('score');
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
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $Foreman = M('foreman');
        $where['id'] = $id;
        $sign_time = $Foreman->where($where)->getField('sign_time');
        if($sign_time + 24*3600 < time() || $sign_time == 0 ){
            $res= $Foreman->where($where)->setInc('score');
            $map['sign_time'] = time();
            $up_res = $Foreman->where($where)->save($map);
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