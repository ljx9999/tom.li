<?php
namespace Home\Controller;
use Home\Model\WorkerModel;
use Think\Controller;
class EvaluateController extends FrontBaseController {
    function __construct() {
        parent::__construct();
        
    }
    /*查看留言首页*/
    public function index(){ 
          
        $this->display();
    }

    /*回复留言*/
    public function reMessage(){
        $id = I('id');
        $re_message = I('re_message');
        $evaluate = M('evaluate');
        $where['id'] = $id;
        $data['re_message'] = $re_message;
        $data['re_time'] = time();
        $res = $evaluate->where($where)->save($data);
        if($res){
            $datas['status']=1;
            $datas['info']="回复成功！";
            $this->ajaxReturn($datas);
        }else{
            $datas['status']=0;
            $datas['info']="回复失败！";
            $this->ajaxReturn($datas);
        }
    }

    /*获取留言*/
    public function getMessage(){
        $worker_id = $_SESSION['fid'];
        $page = I('page');  
        $evaluate = D('evaluate');
        $eva_list = $evaluate -> getMyEvaluates($worker_id,$page);
        $this->ajaxReturn($eva_list);
    }

     /*获取留言*/
    public function getNewMessage(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION['fid'];
        $page = I('page');
        $evaluate = D('evaluate');
        $eva_list = $evaluate -> getMyEvaluates($fid,$page);
        $this->ajaxReturn($eva_list);   
    }
}