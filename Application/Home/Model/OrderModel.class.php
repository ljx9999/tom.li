<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;

        class OrderModel extends Model{
         /**
         * 添加用工需求
         * @access protected
         * @param mixed $data 要返回的数据
         * @param String $type AJAX返回数据格式
         * @return void
         */
            Public function addDemandInfo($data) {
                $demand = M("demand");
                $data = json_decode($data,true);
                $demandInfo = $demand->add($data);
                if($demandInfo){
                    return  array("info"=>"发布成功","status"=>1);
                }else{
                    return  array("info"=>"发布失败","status"=>0);
                }
            }
            /**
             * 下订单
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function addOrderInfo($data) {
                $order = M("Order");
                $data = json_decode($data,true);
                $orderInfo = $order->add($data);
                if($orderInfo){
                    return json_encode(array("info"=>"","status"=>1));
                }else{
                    return json_encode(array("info"=>"","status"=>0));
                }
            }
            /**
             * 查询订单状态
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function checkOrderInfo($data) {
                $order = M("Order");
                if($data['type']=='user'){
                    $orderInfo = $order->where("userid =".$data['id'])->order('id desc')->select();
                    foreach($orderInfo as $key=>$value){
                        $orderInfo[$key]['firstPay'] = $orderInfo[$key]['quote'] * 0.015;
                        $workerInfo = M('Foreman')->where("id = ".$value['worker_id'])->find();
                        $orderInfo[$key]['sub'] = $workerInfo;
                    }
                }else{
                    $orderInfo = $order->where("worker_id =".$data['id'])->order('id desc')->select();
                    foreach($orderInfo as $key=>$value){
                        $demandInfo = M('demand')->where("id = ".$value['demand_id'])->find();
                        $orderInfo[$key]['sub'] = $demandInfo;
                        $orderInfo[$key]['sub']["needAll"] = str_replace("'","",$demandInfo['needAll']); 
                    }
                }
                    return $orderInfo;

            }
            /**
             * 更改订单状态
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function updateOrderInfo($result) {

                $id = $result['id'];
                $quote =  $result['quote'];
                $where['quote'] = $quote;
                $orderInfo = M('order')->where("id = $id")->save($where);
                return $orderInfo;

            }


}