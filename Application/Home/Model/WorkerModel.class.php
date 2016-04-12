<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;
        class ForemanModel extends RelationModel{
            protected $tableName = 'foreman';
            protected $_link = array(
                'ForemanDetail' => array(
                    'mapping_type'    =>self::HAS_ONE,
                    'class_name'    => 'ForemanDetail',
                    'foreign_key' => 'id',
                    'mapping_name' => 'foremandata'
                )
            );
        }
        class WorkerModel extends Model{
         /**
         * 施工方查询
         * @access protected
         * @param mixed $data 要返回的数据
         * @param String $type AJAX返回数据格式
         * @return void
         */
            Public function getWorkerList($page = 0,$type,$sizes,$year,$level,$city) {
                $Foreman = M("foreman");
                if($sizes){
                    $str = "AND `type` like '%".$sizes."%'";
                }else if($year == 6){
                    $str = "AND `work_life` > 6";
                }else if($year > 0 && $year < 6 ){
                    $str = "AND `work_life` = $year";
                }else if($level){
                    $str = "AND `level` = $level";
                }else{
                    $str ="";
                }
                $new_city = $city?$city:"大连";
                $start = $page * 10;
                $sql = "select * from `dd_foreman` where `identity` = $type AND `town` = '".$new_city."' AND `status` = 10 ".$str." ORDER BY level DESC,id DESC limit $start,10";
                $act_arr = $Foreman->query($sql);
                foreach ($act_arr as $key => $val) {
                    $act_arr[$key]['realname'] = $val['realname']?$val['realname']:"-";
                    $act_arr[$key]['company_name'] = $val['company_name']?$val['company_name']:"-";
                    $act_arr[$key]['address'] = $val['address']?$val['address']:"-";
                    $act_arr[$key]['age'] = $val['age']?$val['age']:"-";
                    $act_arr[$key]['work_life'] = $val['work_life']?$val['work_life']:"-";
                    $act_arr[$key]['type'] = $val['type']?$val['type']:"-";
                }
                return  $act_arr;

            }

            /**
             * 施工方内容查询
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function getWorkerContent($id) {
                $Foreman = D("Foreman");
                $id = json_decode($id,true);
                $result = $Foreman->relation(true)->where(array("id"=>$id,'status'=>10,))->find();
                $result['level'] = $result['level']?$result['level']:1;
                if($result){
                    return json_encode($result);
                }else{
                    return json_encode(array("info"=>"","status"=>0));
                }

            }
            /**
             * 收藏状态
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function getCollectContent($id) {
                $Collection = M("Collection");
                $id = json_decode($id,true);
                $collectionStatus =$Collection->where(array("uid"=>$_SESSION['uid'],"worker_id"=>$id))->find();
                if($collectionStatus){
                    return json_encode($collectionStatus);
                }else{
                    return json_encode(array("info"=>"","status"=>0));
                }

            }
            /**
             * 施工方案例查询
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function getWorkerCase($id) {
                $case = M("case_list");
                //列表 
                $sql = "select a.id as pid,a.fid,a.shequ_name,a.content,b.* from `dd_case_list` a left join dd_image b ON a.is_cover = b.id where fid=".$id." and status = 1 limit 10 ";
                $list=$case->query($sql); 
                return $list;

            }
            /**
             *  收藏施工方
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function addCollect($id,$uid) {
                $Collection = M("Collection");
                $Id = json_decode($id);
                $check = $Collection->where(array("uid"=>$uid,"worker_id"=>$Id))->find();
                if($check){
                    if($check['status']==1){
                        $data['status'] = 0;
                    }else{
                        $data['status'] =1;
                    }
                    $data['inputtime'] = time();
                    $result = $Collection->where(array("uid"=>$uid,"worker_id"=>$Id))->save($data);
                    if($result &&  $data['status'] == 1){
                        return json_encode(array("info"=>$result,"status"=>1));
                    }else if($result &&  $data['status'] == 0){
                        return json_encode(array("info"=>$result,"status"=>2));
                    }
                }else{
                    $data['inputtime'] = time();
                    $data['worker_id'] = $Id;
                    $data['uid'] = $uid;
                    $data['status'] = 1;
                    $result = $Collection->add($data);
                    return json_encode(array("info"=>$result,"status"=>1));
                }
                
            }

}