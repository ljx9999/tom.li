<?php
namespace Home\Model;
use Think\Model\RelationModel;
 
use Think\Model;

        class ForemanModel extends Model {
         /**
         * 注册会员
         * @access protected
         * @param mixed $data 要返回的数据
         * @param String $type AJAX返回数据格式
         * @return void
         */
            Public function add($data) {
                $User = M("User");
                $Foreman = M("Foreman");
                $data = json_decode($data,true);
                $checkMobile = $User->where("mobile = ".$data['mobile'])->find();
                $checkMobile2 = $Foreman->where("mobile = ".$data['mobile'])->find();
                if($checkMobile||$checkMobile2){
                    return json_encode(array("info"=>"用户名已存在","status"=>0));
                }else{
                    $addUser = $User->add($data);
                    if($addUser){
                        session('mobile',$data['mobile']);
                        session('uid',$addUser);
                        return json_encode(array("info"=>"注册成功","status"=>1));
                    }else{
                        return json_encode(array("info"=>"数据写入失败","status"=>0));
                    }
                }
            }
            /**
             * 商家注册
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function addStore($data) {
                $User = M("User");
                $Foreman = M("Foreman");
                $data = json_decode($data,true);
                $checkMobile = $User->where("mobile = ".$data['mobile'])->find();
                $checkMobile2 = $Foreman->where("mobile = ".$data['mobile'])->find();
                if($checkMobile||$checkMobile2){
                    return json_encode(array("info"=>"用户名已存在","status"=>0));
                }else{
                    $addForeman = $Foreman->add($data);
                    if($addForeman){
                        session('mobile',$data['mobile']);
                        session('fid',$addForeman);
                        return json_encode(array("info"=>"注册成功","status"=>1));
                    }else{
                        return json_encode(array("info"=>"数据写入失败","status"=>0));
                    }
                }
            }
            /**
             * 会员登录
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function login($data) {

                $Foreman = M("foreman");
                $data = json_decode($data,true);
                $checkMobile = $Foreman->where("mobile = ".$data['mobile'])->find();

                if($checkMobile){
                    $where['lastlogintime'] = time();
                    $Foreman->where("id = ".$checkMobile['id'])->save($where);
                    session('mobile',$data['mobile']);
                    session('fid',$checkMobile['id']);
                    return array("info"=>"登录成功","status"=>1,"fid"=>$checkMobile['id']);  
                }else{
                    return  array("info"=>"用户名不存在","status"=>0);
                }
            }
            /**
             * 查询用户表 用户名是否存在
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function checkMobile($mobile){
                 $User = M("User");
                 $Foreman = M("Foreman");
                 $mobile = json_decode($mobile);
                 $checkMobile = $User->where("mobile = $mobile")->find();
                 $checkMobile2 = $Foreman->where("mobile = $mobile")->find();
                if(!$checkMobile&&!$checkMobile2){
                    echo 'true';
                    exit;
                }else{
                    echo false;
                    exit;
                }
            }
            /**
             * 修改工长资料
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function editForeman($data,$fid) {
                $foreman = M('foreman');
                $saveInfo =$foreman->where("id = ".$fid)->save($data);
                return array("info"=>$saveInfo,"status"=>1);
            }

                
            public function userInformation($data){
                $Foreman=M("Foreman");
                if($_SESSION['fid']){
                    $checkMobile = $Foreman->where("id = ".$_SESSION['fid'] )->find();
                    return array("info"=>$checkMobile,"status"=>1,'type'=>'fid');
                }else{
                    return array("info"=>"登录超时，请重新登录","status"=>0);
                }
                }
            /**
             * 查询装修工信息
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function userInfoModel($id="") {
                $Foreman = M("Foreman");
                $checkMobile = $Foreman->where("id = ".$id )->find();
                $checkMobile["income"] = ForemanModel::getUserMoney($id);
                return $checkMobile;
            }
            /**
             * 案例上传
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function caseUpload($data) {
                $case = M("case");
                $data = json_decode($data,true);
                $result = $case->add($data);
                if($result){
                    return  array("info"=>"添加成功","status"=>1);
                }else{
                    return  array("info"=>"添加失败","status"=>0);
                }
            }
            /**
             * 获取账户信息
             * @access public
             * 
             */
            public function getUserMoney($id){
                $where['worker_id'] = $id;
                $where['status'] = array(array('eq',10),array('eq',15), 'or');
                $order = M('order');
                $res = $order->where($where)->select();
                $half_count = 0;
                $all_count = 0;
                foreach ($res as $key => $value) {
                    $half_count+=($value['quote']*0.3);
                }
                $datas['worker_id'] = $id;
                $datas['status'] = array(array('eq',20),array('eq',25), 'or');
                $rell = $order->where($datas)->select();
                 foreach ($rell as $k=> $v) {
                    $all_count+=$v['quote'];
                }
                $counts = $half_count + $all_count;
                return $counts?$counts:0;

            }

}