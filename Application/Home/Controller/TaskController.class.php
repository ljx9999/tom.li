<?php
namespace Home\Controller;
use Home\Model\TaskModel;
use Think\Controller;
use Home\Model\WechatMessageModel;
use Home\Model\SmsModel;
class TaskController extends FrontBaseController {

    public function index(){
        $this->display();
    }

      /*获取需求列表*/
    public function ajaxGetTaskList(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION['fid'];
        $page = I('page');
        $data['id'] = $id;
        $foreman = M('foreman');
        $city =  $foreman->where($data)->getField('town');
        $city =  $city?$city:"大连";
        $demandList = TaskModel::getTaskList($id,$page,$city);
        $datas['info'] = $demandList;
        $this->ajaxReturn($datas);
    }

    public function getMyTask(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION['fid'];
        $demandList = TaskModel::getMyTask($id);
        if(!empty($demandList)){
            $datas['status'] = 1;
            $datas['info'] = $demandList;
        }else{
            $datas['status'] = 0;
            $datas['info'] = '没有符合条件的招标';
        }
       
        $this->ajaxReturn($datas);
    }

    /*施工方竞标*/
    public function ajaxSubTask(){
        $fid = I('fid');
        $id = $fid?$fid:$_SESSION['fid'];
        $data['programid'] =  I('demand_id');
        $data['content'] = I('worker_text');
        $data['uid'] = I('uid');
        $data['fid'] = $id;
        $data['create_time'] = time();
        $data['status'] = 1;
        $data['quote'] = I('quote');
        $data['work_time'] = I('work_time');
        $data['worker_name'] = I('worker_name');
        $data['worker_mobile'] = I('worker_mobile');
        $Feedback = M("Feedback");
        $result = $Feedback->add($data);
        if($result){
            $user = M('user');
            $userInfo = $user->where('userid='.$data['uid'])->find();
            $tem_id = "XFjLo35IeOHpMV5y8vCszVJM5FTiax4w9x8eMaROjgc";
            $date = date("Y-m-d",time());
            $data = array(
                'first'=>array('value'=>urlencode("您好,您的需求有一个新的投标，请查看"),'color'=>"#743A3A"),
                'keyword1'=>array('value'=>urlencode($date)),
                'keyword2'=>array('value'=>urlencode($data['worker_name'])),
                'keyword3'=>array('value'=>urlencode($data['quote']."元")),
                'remark'=>array('value'=>urlencode('更具体的详情，请查看详情.'),'color'=>'#FFFFFF'),
            );
            $url = "http://www.didifree.com/didifree/User/review_home?openid=".$userInfo['openid'];
            WechatMessageModel::wechatInfo($userInfo['openid'],$data,$tem_id,$url);
            $message = '你有新的投标请查看';
            SmsModel::send_message($message,$userInfo['tag']);
            $datas['status']=1;
            $datas['info']="竞标成功！";
            $this->ajaxReturn($datas);
        }else{
            $datas['status']=0;
            $datas['info']="竞标失败！";
            $this->ajaxReturn($datas);
        }
    }
    
}