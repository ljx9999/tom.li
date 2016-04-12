<?php
namespace Home\Controller;
use Home\Model\WechatModel;
use Think\Controller;
class IndexController extends FrontBaseController {

    public function index(){
        if(I('openid')){
            $user = M('user');
            $where['openid'] = I('openid');
            $rell = $user->where($where)->find();
            $foreman = M('foreman');
            $data['openid'] = I('openid');
            $ress = $foreman->where($data)->find();
            if($rell){
                $wheres['lastlogintime'] = time();
                $user->where("userid = ".$rell['userid'])->save($wheres);        
                session('mobile',$rell['mobile']);
                session('uid',$rell['userid']);
                session('openid',I('openid'));
                redirect(__APP__.'/Home/Home/index');
            }else if($ress){
                $wheres['lastlogintime'] = time();
                $foreman->where("id = ".$ress['id'])->save($wheres);
                session('mobile',$ress['mobile']);
                session('fid',$ress['id']);
                session('openid',I('openid'));
                redirect(__APP__.'/Home/Home/index_gz');
            }
        }else{
            $code =$_GET['code'];
            $access_token = WechatController::get_access_token(json_encode($code));
            $result = json_decode($access_token,true);
            session("accessToken",$result);
            $user = M('user');
            $where['openid'] = $_SESSION["accessToken"]["openid"];
            $rell = $user->where($where)->find();
            $foreman = M('foreman');
            $data['openid'] = $_SESSION["accessToken"]["openid"];
            $ress = $foreman->where($data)->find();
            if($rell){
                session('mobile',$rell['mobile']);
                session('uid',$rell['userid']);
                session('openid',$_SESSION["accessToken"]["openid"]);
                redirect(__APP__.'/Home/Home/index');
            }else if($ress){
                session('mobile',$ress['mobile']);
                session('fid',$ress['id']);
                session('openid',$_SESSION["accessToken"]["openid"]);
                redirect(__APP__.'/Home/Home/index_gz');
            }else{
                $this->display();
            }
        }
       
    }

    
}