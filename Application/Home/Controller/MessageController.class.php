<?php
namespace Home\Controller;
use Think\Controller;
use Home\Model\MessageModel;
class MessageController extends FrontBaseController {
        /*留言页*/
        public function index(){
			// var_dump($_SESSION);
            $this->display();
        }
    /*提交留言*/
    public function Message(){
		$fid = I('uid');
        $id = $fid?$fid:$_SESSION['uid'];
		$data['cat_name']= I('cat_name');
		$data['message']= I('message');
		$data['add_time'] = time();
		$data['user_id'] = $id;
        $data['status'] = 0;
		$result = MessageModel::addMessage(json_encode($data));
		$info = json_decode($result,true);

		if($info['status']==1){
			$datas['status'] = 1;
            $datas['info'] = "留言成功！";
            $this->ajaxReturn($datas,'JSON');
		}else{
		    $datas['status'] = 0;
            $datas['info'] = "留言失败！";
            $this->ajaxReturn($datas,'JSON');
		}

    }

} 