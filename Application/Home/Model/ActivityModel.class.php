<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;
class ActivityModel extends Model{
 /**
 * 最新活动查询查询
 * @access public
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @return void
 */
    /*获取活动列表*/
    public function getActsList($page){
        $act_arr = $this->getActProList($page);
        return $act_arr;
    }
    /*获取活动列表方法*/
    public function getActProList($page,$type){
        $activity = M('activity');
        $act_arr = array();
        $where['type'] = 2;
        $where['status'] = 1;
        $time = time();
        $where['end_time'] =  array('gt',$time); 
        $order = "act_order DESC";
        $count =  $activity->where($where)->count();// 查询满足要求的总记录数     
        $start = $page * 3;         
        $sql = "select * from `dd_activity` where `type` = $type AND `status` = 1 AND `end_time` > $time ORDER BY act_order DESC limit $start,3";
        $act_arr = $activity->query($sql);
        foreach ($act_arr as $key => $value) {
            $act_arr[$key]['start_time'] = date('Y-m-d',$value['start_time']);
            $act_arr[$key]['end_time'] = date('Y-m-d',$value['end_time']);
        }
        return $act_arr;
    }
    /*获取活动详细内容*/
    public function getOneAct($id){
        $activity = M('activity');
        $where['id'] = $id;
        $acts = $activity->where($where)->find();
        return $acts;
    }

}