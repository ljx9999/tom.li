<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;

        class UserModel extends Model{
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
			    $where['mobile'] = $data['mobile'];
                $where['openid'] = $_SESSION["accessToken"]["openid"];
                $where['_logic'] = 'OR';
                $checkMobile = $User->where($where)->find();
                $checkMobile2 = $Foreman->where("mobile = ".$data['mobile'])->find();
                if($checkMobile){
                    return  array("info"=>"该手机已经注册过用工方。","status"=>0);
                    exit;
                }else if($checkMobile2){
                    return  array("info"=>"该手机已经注册过施工方，不能再注册用工方。","status"=>0);
                    exit;
                }else{
                    $result = UserModel::get_user_info();
                    if($result){
                        $data2 = json_decode($result,true);
                        $dataAll= array_merge($data,$data2);
                    }else{
                        $dataAll =$data;
                    }

                    $addUser = $User->add($dataAll);
				
                    if($addUser){
                        session('mobile',$data['mobile']);
                        session('uid',$addUser);
                        return array("info"=>"注册成功","status"=>1,"uid"=>$addUser);
                    }else{
                        return array("info"=>"注册失败","status"=>0);
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
                $User = M("user");
                $Foreman = M("foreman");
                $data = json_decode($data,true);
                $where['mobile'] = $data['mobile'];
                $where['openid'] = $_SESSION["accessToken"]["openid"];
                $where['_logic'] = 'OR';
                $checkMobile = $User->where("mobile = ".$data['mobile'])->find();
                $checkMobile2 = $Foreman->where($where)->find();
                if($checkMobile){
                    return  array("info"=>"该手机已经注册过用工方，不能再注册施工方。","status"=>0);
                    exit;
                }else if($checkMobile2){
                    return  array("info"=>"该手机已经注册过施工方。","status"=>0);
                    exit;
                }else{
                    $result = UserModel::get_user_info();
                    $result_arr = json_decode($result,true);
                    $data['openid']   = $_SESSION["accessToken"]["openid"];
                    $data['head_image'] = $result_arr['headimgurl'];
                    $addForeman = $Foreman->add($data);
                    if($addForeman){
                        session('mobile',$data['mobile']);
                        if($_SESSION['fid']){
                            session('fid',null);
                        }
                        session('fid',$addForeman);
                        return array("info"=>"注册成功","status"=>1,"fid"=>$addForeman);
                    }else{
                        return array("info"=>"注册失败","status"=>0);
                    }
                }
            }

			/**
             * 用户验证手机修改密码
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
			 Public function loginpw($data){
				 $User=M("user");
				 $data=json_decode($data,true);
				 $checkMobile = $User->where("mobile = ".$data['mobile'])->find();
				 if($checkMobile){
					 session('mobile',$data['mobile']);
					 return array("info"=>"成功","status"=>1);
				 }else{
					 return array("info"=>"用户不存在","status"=>0);
				 }
			 }
			 /**
             * 商户验证手机修改密码
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
			 Public function loginpw_gz($data){
				$Foreman=M("foreman");
				$data=json_decode($data,true);
                $checkMobile2 = $Foreman->where("mobile = ".$data['mobile'])->find();
				 if($checkMobile2){
					 session('mobile',$data['mobile']);
					 return array("info"=>"成功","status"=>1);
				 }else{
					 return array("info"=>"用户不存在","status"=>0);
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
                $User = M("user");
                $data = json_decode($data,true);
                $checkMobile = $User->where("mobile = ".$data['mobile'])->find();

                if($checkMobile){
                    $where['lastlogintime'] = time();
                    $User->where("userid = ".$checkMobile['userid'])->save($where);
                    session('mobile',$data['mobile']);
                    session('uid',$checkMobile['userid']);
                    return array("info"=>"登录成功","status"=>1,"uid"=>$checkMobile['userid']);
                }else{
                    return array("info"=>"用户名不存在","status"=>0);
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
                    $Foreman = M("foreman");
				    $User = M("user");

                 $checkMobile = $User->where("mobile = $mobile")->field('userid')->find();
                 $checkMobile2 = $Foreman->where("mobile = $mobile")->field('id')->find();
            }
            /**
             * 修改用户资料
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function edit($data,$uid) {
                $User = M("user");
                $checkMobile = $User->where("userid = ".$uid)->find();
                if($checkMobile){
                    $saveUser = $User->where("userid = ".$uid)->save($data);
                    if($saveUser){
                        return array("info"=>"修改成功","status"=>1);
                    }else{
                        return array("info"=>"修改失败","status"=>0);
                    }
                }else{
                    return array("info"=>"登录超时，请重新登录","status"=>2);
                }

            }
            
		    /**
             * 用户修改密码
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
		    public function setpassword($data) {
                $User = M("user");
                $data = json_decode($data,true);
                $checkMobile = $User->where("mobile = ".$_SESSION['mobile'])->find();
                if($checkMobile){
                    $savepassword = $User->where("mobile = ".$_SESSION['mobile'])->save($data);
                    if($savepassword){
                        return array("info"=>"修改成功","status"=>1);
                    }else{
                        return array("info"=>"修改失败","status"=>0);
                    }
                }else{
                    return array("info"=>"登录超时，请重新登录","status"=>2);
                }

                }

			/**
             * 修改工长资料
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function editForeman($data) {
                $Foreman = D("Foreman");
                $data = json_decode($data,true);
                $checkMobile = M()->table('foreman_detail')->where("id = ".$data['id'])->find();
                if($checkMobile){
                    $saveUser = M()->table('foreman_detail')->where("id = ".$checkMobile['id'])->save($data);
                }else{
                    $saveUser = M()->table('foreman_detail')->where("id = ".$checkMobile['id'])->add($data);
                }
                if($checkMobile&&$saveUser){
                    return json_encode(array("info"=>"修改成功","status"=>1));
                }else{
                    return json_encode(array("info"=>"修改失败","status"=>0));
                }
            }
		    
			/**
             * 商家修改密码
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
		    public function setpassword_gz($data) {
                $Foreman = M("foreman");
                $data = json_decode($data,true);
                $checkMobile2= $Foreman->where("mobile = ".$_SESSION['mobile'])->find();
                if($checkMobile2){
                    $savepassword = $Foreman->where("mobile = ".$_SESSION['mobile'])->save($data);
                    if($savepassword){
                        return array("info"=>"修改成功","status"=>1);
                    }else{
                        return array("info"=>"修改失败","status"=>0);
                    }
                }else{
                    return array("info"=>"登录超时，请重新登录","status"=>2);
                }

                }
            /**
             * 查询用户信息
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function userInfoModel($data) {
                $User = M("User");
                $Foreman = D("Foreman");
                if($_SESSION['uid'] ){
                    $checkMobile = $User->where(array("mobile"=>$_SESSION['mobile'],"userid"=>$_SESSION['uid']))->find();
                    return array("info"=>$checkMobile,"status"=>1,'type'=>'uid');
                }elseif($_SESSION['fid']){
                    $checkMobile = $Foreman->where("mobile = ".$_SESSION['mobile'] and "id = ".$_SESSION['fid'] )->find();
                    return array("info"=>$checkMobile,"status"=>1,'type'=>'fid');
                }else{
                    return array("info"=>"登录超时，请重新登录","status"=>0);
                }
            }
            /**
             * 收藏列表
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            Public function getCollectionList($uid) {
                $Collection= M("Collection");
                $result = $Collection->where("uid =".$uid." and status = 1")->order("inputtime desc")->select();
                foreach($result as $key=>$value){
                    $foremanList = M('foreman')->where("id = ".$value['worker_id'])->find();
                    $list[$key]['sub'] = $foremanList;
                    $list[$key]['sub']['worker_id'] = $value['worker_id'];
                    $list[$key]['sub']['id'] = $value['id'];
                }
                return $list;
            }
            /**
             * 文件上传
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
            public function _upload() {
                import('@.ORG.UploadFile');
                //导入上传类
                $upload = new \UploadFile();
                //设置上传文件大小
                $upload->maxSize            = 3292200;
                //设置上传文件类型
                $upload->allowExts          = explode(',', 'jpg,gif,png,jpeg');
                //设置需要生成缩略图，仅对图像文件有效
                $upload->thumb              = false;
                // 设置引用图片类库包路径
                $upload->imageClassPath     = '@.ORG.Image';
                //设置需要生成缩略图的文件后缀
                $upload->thumbPrefix        = 'm_,s_';  //生产2张缩略图
                //设置缩略图最大宽度
                $upload->thumbMaxWidth      = '400,100';
                //设置缩略图最大高度
                $upload->thumbMaxHeight     = '400,100';
                //设置上传文件规则
                $upload->saveRule           = 'uniqid';
                //删除原图
                $upload->thumbRemoveOrigin  = true;
                $upload->autoSub = true;                      //是否使用子目录保存上传文件
                $upload->subType = 'date';                      //子目录创建方式，默认为hash，可以设置为hash或者date
                $upload->dateFormat = 'Ym';
                //设置附件上传目录
                $upload->savePath           = './Uploads/';
                if (!$upload->upload()) {
                    //捕获上传异常
                    $this->error($upload->getErrorMsg());
                } else {
                    //取得成功上传的文件信息
                   return $uploadList = $upload->getUploadFileInfo();
                    //import('@.ORG.Image');
                    //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
        //             Image::water($uploadList[0]['savepath'] . 'm_' . $uploadList[0]['savename'], APP_PATH.'Tpl/Public/Images/logo.png');
        //            $_POST['image'] = $uploadList[0]['savename'];
                }

            }

            /**
             * 检验手机短信码
             * @access protected
             * @param mixed $data 要返回的数据
             * @param String $type AJAX返回数据格式
             * @return void
             */
                public function checkSmsCode($data){
                    $data = json_decode($data,true);
                    $checkSms = M('sms_verification')->where("mobile =". $data['mobile'])->order("create_time desc")->find();
                    if($checkSms['code']==$data['code']){
                        return  array("info"=>'有效验证码',"status"=>1);
                    }else{
                        return  array("info"=>'验证码错误',"status"=>0);
                    }
                }

            /**
             * 获取授权后的微信用户信息
             *
             * @param string $access_token
             * @param string $open_id
             */
            public function get_user_info($access_token = '', $open_id = '')
            {
                $access_token = $_SESSION["accessToken"]["access_token"];
                $open_id = $_SESSION["accessToken"]["openid"];
                if($access_token && $open_id)
                {
                    $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";

                    $info_data = UserModel::http($info_url);

                    $data['nickname']     = $info_data->nickname;
                    $data['sex']   = $info_data->sex;
                    $data['city']   = $info_data->city;
                    $data['headimgurl']   = $info_data->headimgurl;
					$data['openid']   = $_SESSION["accessToken"]["openid"];
                    return json_encode($data);
                }

                return FALSE;
            }
            public function http($url, $method, $postfields = null, $headers = array(), $debug = false)
            {
                $curl = curl_init ( $url );
                curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, false ); // SSL证书认证
                curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 0 ); // 不认证
                curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
                curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 显示输出结果

                $responseText = curl_exec ( $curl );
                $json = json_decode ( $responseText );
                curl_close ( $curl );
                return $json;

            }

            /*获取轮播图片*/
            public function getImgList($type){
                $act = M('activity');
                $where['type'] = $type;
                $img_list = $act->where($where)->order('act_order desc')->limit(3)->select();
                return $img_list;
            }


}