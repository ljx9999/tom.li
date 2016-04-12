<?php
namespace Home\Controller;
use Home\Model\ActivityModel;
use Think\Controller;
class ActivityController extends FrontBaseController {
    function __construct() {
        parent::__construct();
    }
    /*最新活动首页*/
    public function index(){
        $this->display();
    }

    /*获取最新活动列表*/
    public function ajaxGetActList(){
        // if($_SESSION['uid']){
        //     $type = 2;
        // }else if($_SESSION['fid']){
        //     $type = 4;
        // }
        $type = 2;
        $page = I('page');
        $ActivityModel = D('Activity');
        $act_list = $ActivityModel->getActProList($page,$type);
        $data['info'] = $act_list;
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    /*活动详情页面*/
    public function detail(){
        $id = I('id');
        $this->assign('id',$id);
        $this->display();
    }
    
    /*获取详情页内容*/
    public function getDetailInfo(){
        $id = I('id');
        if($id > 0){
            $ActivityModel = D('Activity');
            $acts = $ActivityModel->getOneAct($id);
            $acts['act_description'] = htmlspecialchars_decode($acts['act_description']);
        }
        $this->ajaxReturn($acts);
    }



}