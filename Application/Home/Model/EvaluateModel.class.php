<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;
class EvaluateModel extends RelationModel{
    /*获取留言列表*/
    public function getMyEvaluates($forman_id,$page){
        $eva_arr = $this->getEvaluateList($forman_id,$page);
        return $eva_arr;
    }
    /*获取留言*/
    public function getEvaluateList($forman_id,$page){
        $evaluate = M('evaluate');
        $users = M('user');
        $eva_arr = array();
        $where['status'] = 1;
        $where['foreman_id'] = $forman_id; 
        $order = "id DESC";
        $count =  $evaluate->where($where)->count();// 查询满足要求的总记录数     
        $start = $page * 3;         
        $sql = "select * from `dd_evaluate` where `status` = 1 AND `foreman_id` = $forman_id ORDER BY id DESC limit $start,3";
        $eva_arr = $evaluate->query($sql);
        foreach ($eva_arr as $key => $val) {
            $eva_arr[$key]['add_time'] = date('Y-m-d H:i:s',$val['add_time']);
            $wheres['userid'] = $val['user_id'];
            $userList = $users->where($wheres)->find();
            $eva_arr[$key]['realname'] = $userList['realname'];
            // $evaluate_list[$key]['img_src'] = $userList['realname'];
        }
        return $eva_arr;
    }

}
        
