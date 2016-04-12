<?php
namespace Home\Controller;
use Home\Model\UserModel;
use Home\Model\ForemanModel;
use Think\Controller;
class HomeController extends FrontBaseController {

    public function index(){
        if($_SESSION['uid']){
            $this->assign('uid',$_SESSION['uid']);
        }
        $this->display();
    }

    public function getIndex(){
        $uid = I('uid');
        $id = $uid?$uid:$_SESSION['uid'];
        $type =1;
        $imgList = UserModel::getImgList($type);
        $feedback=M('feedback');
        $where['uid'] = $id;
        $where['status'] = 1;
        $result['jb_num'] = $feedback->where($where)->count();
        if($result['jb_num'] > 10){
            $result['jb_num'] = 11;
        }
        $order = M('order');
        $map['status'] = 10;
        $map['userid'] =$id;
        $result['sf_num'] = $order->where($map)->count();
        if($result['sf_num'] > 10){
            $result['sf_num'] = 11;
        }
        $maps['status'] = 15;
        $maps['userid'] = $id;
        $result['ys_num'] = $order->where($maps)->count();
        if($result['ys_num'] > 10){
            $result['ys_num'] = 11;
        }
        $htm['status'] = 20;
        $htm['userid'] = $id;
        $result['pj_num'] = $order->where($htm)->count();
        if($result['pj_num'] > 10){
            $result['pj_num'] = 11;
        }
        session('number',$result);
        $data['imgList'] = $imgList;
        $data['list'] = $_SESSION['number'];
        $this->ajaxReturn($data);
    }

    
     public function index_gz(){
    
        $this->display();
    }

    public function getForemanIndex(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION["fid"];
        $type =3;
        $imgList = UserModel::getImgList($type);
        $demand = M('demand');
        $where['add_time']  = array('gt',$info['info']['lastlogintime']);
        $where['fid'] = 0;
        $where['status'] = 0;
        $result['xq_num'] = $demand->where($where)->count();
        if($result['xq_num'] > 10){
            $result['xq_num'] = 11;
        }
        $order = M('order');
        $map['status'] = 1;
        $map['worker_id'] =$id;
        $result['zb_num'] = $order->where($map)->count();
        if($result['zb_num'] > 10){
            $result['zb_num'] = 11;
        }
        $datas['status'] = 10;
        $datas['worker_id'] = $id;
        $result['sf_num'] = $order->where($datas)->count();
        if($result['sf_num'] > 10){
            $result['sf_num'] = 11;
        }
        $maps['status'] = 15;
        $maps['worker_id'] =$id;
        $result['ys_num'] = $order->where($maps)->count();
        if($result['ys_num'] > 10){
            $result['ys_num'] = 11;
        }
        $Foreman = M("foreman");
        $fores['id'] = $id;
        $realname = $Foreman->where($fores)->getField('realname');
        session('number',$result);
        $data['imgList'] = $imgList;
        $data['list'] = $_SESSION['number'];
        $data['realname'] = $realname;
        $this->ajaxReturn($data);
    }

  

	 
}