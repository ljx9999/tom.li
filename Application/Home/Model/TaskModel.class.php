<?php
namespace Home\Model;
use Think\Model;

        class TaskModel extends Model
        {
            /**
             * 需求列表
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            /*获取需求列表方法*/
            public function getTaskList($id,$page,$city){
                $Demand = M('demand');
                $demand_arr = array();
                $start = $page * 4;         
                $sql = "select * from `dd_demand` where `fid` = 0 AND `status` = 0 AND `city` = '".$city."'  ORDER BY id DESC limit $start,4";
                $demand_arr = $Demand->query($sql);
                $foreman = M('foreman');
                $result = $foreman->where('id='.$id)->find();
                $foreman_name = $result['realname']?$result['realname']:$result['company_name'];
                $feedback = M('feedback');
                foreach ($demand_arr as $key => $val) {
                    if($val['needAll']){
                        $demand_arr[$key]["needAll"] = str_replace("'","",$val['needAll']); 
                    }
                    $demand_arr[$key]["add_time"] = date('Y-m-d H:i:s',$val['add_time']);
                    $wheres['fid'] = $id;
                    $wheres['programid'] = $val['id'];
                    $res = $feedback->where($wheres)->find();
                    if($res){
                       $demand_arr[$key]["baojia"] = 1;
                    }else{
                        $demand_arr[$key]["baojia"] = 0;
                    }
                    $demand_arr[$key]['foreman_name'] = $foreman_name;
                    $demand_arr[$key]['foreman_mobile'] = $result['mobile'];
                }
                return $demand_arr;
            }
            
            /*获取单独下单的需求*/
            public function getMyTask($id){
                $Demand = M("demand");
                $feedback = M('feedback');
                $foreman = M('foreman');
                $where['status'] = 0;
                $where['fid'] = $id;
                $result = $foreman->where('id='. $id)->find();
                $foreman_name = $result['realname']?$result['realname']:$result['company_name'];
                //列表值
                $list = $Demand->where($where)->order('id desc')->select();
                foreach ($list as $key => $val) {
                    if($val['needAll']){
                        $list[$key]["needAll"] = str_replace("'","",$val['needAll']); 
                    }
                    $list[$key]["add_time"] = date('Y-m-d H:i:s',$val['add_time']);
                    $wheres['fid'] =  $id;
                    $wheres['programid'] = $val['id'];
                    $res = $feedback->where($wheres)->find();
                    if($res){
                       $list[$key]["baojia"] = 1;
                    }else{
                        $list[$key]["baojia"] = 0;
                    }
                    $list[$key]['foreman_name'] = $foreman_name;
                    $list[$key]['foreman_mobile'] = $result['mobile'];
                }
                
                return $list;
            }


            /**
             *  
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function getReviewList($data)
            {
                $Feedback = D("Feedback");
                $result = $Feedback->where($data)->order('id desc')->select();
                $order = M('order');
                $wheres['userid'] = $data["uid"];
                foreach ($result  as $k => $val) {
                    $wheres['demand_id'] = $val['programid'];
                    $res = $order->where($wheres)->find();
                    if($res['id']){
                        $result[$k]['have_order'] = 1;
                        $result[$k]['feedback_id'] = $res['feedback_id'];
                    }else{
                        $result[$k]['have_order'] = 0;
                        $result[$k]['feedback_id'] = $res['feedback_id']?$res['feedback_id']:0;
                    }
                }
                return $result;
            }

        }