<?php
namespace Home\Controller;
use Think\Controller;
class FrontBaseController extends Controller {
    public function __construct(){

        //取系统配置
        parent::__construct();
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
               
            }else if($ress){
                $wheres['lastlogintime'] = time();
                $foreman->where("id = ".$ress['id'])->save($wheres);
                session('mobile',$ress['mobile']);
                session('fid',$ress['id']);
                session('openid',I('openid'));
            }
        }
        Load('extend');//调取扩展函数库
        $this->assign("js_dir",__ROOT__."/Application/Home/Public/js");
        $this->assign("uploads_dir",__ROOT__."/Application/Home/Uploads");
        $this->assign("img_dir",__ROOT__."/Application/Home/Public/images");
        $this->assign("css_dir",__ROOT__."/Application/Home/Public/css");
        $this->assign("root_dir",__ROOT__."/Uploads");
        $this->assign("image_dirs",__ROOT__."/Uploads");

      


    }
    public function LoginOut(){
        session('mobile',null);
        session('uid',null);
        session('fid',null);
        redirect("../Index/index");
    }
}