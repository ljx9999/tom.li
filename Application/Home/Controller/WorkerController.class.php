<?php
namespace Home\Controller;
use Home\Model\WorkerModel;
use Home\Model\TaskModel;
use Think\Controller;
class WorkerController extends FrontBaseController {
    function __construct() {
        parent::__construct();

    }
    /*找施工队首页*/
    public function index(){
        $type = I('type');
        $type=$type?$type:0;
        $size = I('work_type');
        $size=$size?$size:'';
        $year = I('year');
        $year=$year?$year:'';
        $level = I('level');
        $level = $level?$level:'';
        $this->assign('type',$type);
        $this->assign('size',$size);
        $this->assign('year',$year);
        $this->assign('level',$level);
        $this->display();
    }
   /* 施工队列表*/
    public function ajaxGetWorkerList(){
        $type =  $_REQUEST['type'];
        $size =  $_REQUEST['sizes'];
        $year =  $_REQUEST['years'];
        $level = $_REQUEST['level'];
        $page = I('page');
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $user = M('user');
        $town = $user->where('userid = '.$uid)->getField('town');
        $workerList = WorkerModel::getWorkerList($page,$type,$size,$year,$level,$town);
        $this->ajaxReturn($workerList);
    }
    /*施工队内容*/
    public function show(){
        $Id = I('id');
        $uid = $_SESSION['uid'];
        $User = M('user');
        $bj = I('bj');
        $workerContent = WorkerModel::getWorkerContent(json_encode($Id));
        $CollectContent = WorkerModel::getCollectContent(json_encode($Id));
        $mobile = $User->where('userid ='.$uid)->getField('mobile');
        $realname = $User->where('userid ='.$uid)->getField('realname');
        $result = json_decode($workerContent,true);
        $result2 = json_decode($CollectContent,true);
        $history = M('history');
        $data['fid'] = $Id;
        $data['uid'] = $uid;
        $data['add_time'] = time();
        $data['status'] = 1;
        $history->add($data);
        $this->assign('mobile',$mobile);
        $this->assign('userName',$realname);
        $this->assign("info",$result);
        $this->assign("info2",$result2);
        $this->assign("bj",$bj);
        $this->assign("uid",$uid);
        $this->display();
    }

    public function ajaxShow(){
        $Id = I('id');
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $User = M('user');
        $bj = I('bj');
        $workerContent = WorkerModel::getWorkerContent(json_encode($Id));
        $CollectContent = WorkerModel::getCollectContent(json_encode($Id));
        $mobile = $User->where('userid ='.$uid)->getField('mobile');
        $realname = $User->where('userid ='.$uid)->getField('realname');
        $result = json_decode($workerContent,true);
        $result2 = json_decode($CollectContent,true);
        $history = M('history');
        $data['fid'] = $Id;
        $data['uid'] = $uid;
        $data['add_time'] = time();
        $data['status'] = 1;
        $history->add($data);
        $datas['mobile'] = $mobile;
        $datas['userName'] = $realname;
        $datas['fid'] = $Id;
        $datas['info'] = $result;
        $datas['info2'] = $result2;
        $this->ajaxReturn($datas);


    }

    /*案例展示*/
    public function show_case(){
        $Id= $_GET['id'];
        $workerCase = WorkerModel::getWorkerCase($Id);
        $this->assign("caseList",$workerCase);
        $this->display();
    }

     /*案例展示*/
    public function ajaxCase(){
        $Id= $_GET['id'];
        $workerCase = WorkerModel::getWorkerCase($Id);
        $this->ajaxReturn($workerCase);
        
    }

    /* 收藏施工方*/
    public function collectAct(){
        $Id = I('id');
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $result = WorkerModel::addCollect($Id,$uid);
        $info = json_decode($result,true);
        if($info['status']==1){
            $data['status']=1;
            $data['info']=$info;
            $this->ajaxReturn($data);
        }else if($info['status']==2){
            $data['status']=2;
            $data['info']="取消收藏";
            $this->ajaxReturn($data);
        }else{
            $data['status']=0;
            $this->ajaxReturn($data);
        }
    }
    /*业主留言列表*/
    public function show_review(){
        $Feedback = D("evaluate");
        $data['foreman_id'] = $_GET['id'];
        $data['status'] = 1;
        $result = $Feedback->where($data)->select();
         
        $user = M('user');
        foreach ($result  as $k => $val) {
            $wheres['userid'] = $val['user_id'];
            $res = $user->where($wheres)->find();
            $result[$k]['user_name'] = $res['nickname'];    
        }
        $this->assign("reviewList",$result);
        $this->assign("mobile",$_SESSION['mobile']);
        $this->display();
    }

    /*业主留言列表*/
    public function ajaxReview(){
        $Feedback = D("evaluate");
        $data['foreman_id'] = $_GET['fid'];
        $data['status'] = 1;
        $result = $Feedback->where($data)->select();
         
        $user = M('user');
        foreach ($result  as $k => $val) {
            $wheres['userid'] = $val['user_id'];
            $res = $user->where($wheres)->find();
            $result[$k]['user_name'] = $res['nickname'];    
        }
        $datas['reviewList'] = $result;
        $this->ajaxReturn($datas);

    }

    /* 业主留言*/
    public function feedback(){
        $data['uid'] = $_SESSION['uid'];
        $data['status'] = 1;
        $data['fid'] = I('fid');
        $data['create_time'] = time();
        $data['content'] = I('content');
        $result = TaskModel::addFeedBack(json_encode($data));
        $info = json_decode($result,true);
        if($info){
            $this->success("留言成功","../Worker/show_review/id/".I('fid'));
        }else{
            $this->error("留言失败","../Worker/show_review/id/".I('fid'));
        }
    }

    /* 业主留言*/
    public function ajaxFeedback(){
        $userid = I('uid');
        $uid = $userid?$userid:$_SESSION['uid'];
        $data['uid'] = $uid;
        $data['status'] = 1;
        $data['fid'] = I('fid');
        $data['create_time'] = time();
        $data['content'] = I('content');
        $Feedback = M("Feedback");
        $result = $Feedback->add($data);
        if($result){
            $datas['status'] = 1;
            $datas['info'] = "留言成功";
            $this->ajaxReturn($datas);
        }else{
            $datas['status'] = 0;
            $datas['info'] = "留言失败";
            $this->ajaxReturn($datas);
        }
    }

    /*案例详情*/
    public function show_case_detail(){
        $id = I('id');
        $image = M('image');
        $where['p_id'] = $id;
        $image_list = $image->where($where)->select();
        $this->assign('image_list',$image_list);
        $this->display();
    }

    /*案例详情*/
    public function ajaxDetail(){
        $id = I('id');
        $image = M('image');
        $where['p_id'] = $id;
        $image_list = $image->where($where)->select();
        $this->ajaxReturn($image_list);
    }
}