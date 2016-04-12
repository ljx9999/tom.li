<?php
namespace Home\Controller;
use Home\Model\SmsModel;
use Home\Model\UserModel;
use Home\Model\ForemanModel;
use Think\Controller;
class LoginController extends FrontBaseController {
        /*用户登录页面*/
        public function index(){
			
            $this->display();
           
        }
        /*施工方登录*/
        public function index_gz(){
            
            $this->display();
           
        }
    
        /*用户注册页面*/
        public function register(){
            $this->display();
        }

        /*工人注册页面*/
        public function register_gr(){
            $this->display();
        }

        /*工长注册页面*/
        public function register_gs(){
            $this->display();
        }

		/*用户找回密码页面*/
        public function recover(){
            $this->display();
        }
		public function set_password(){
			$this->display();
		}
		/*商户找回密码页面*/
        public function recover_gz(){
            $this->display();
        }
		public function set_password_gz(){
			$this->display();
		}

        /*注册用户*/
        public function regAct() {
            $l['mobile']= I("mobile");
            $l['code'] = I('sms_code');
            $checkSms = UserModel::checkSmsCode(json_encode($l));
            if($checkSms['status'] != 1){
               $this->ajaxReturn($checkSms);
            }
            $address = I('address');
            if($address){
                $data['town'] = $address['city'];  
            }else{
                $data['town'] = I('city');
            }
            $data['realname'] = I('realname');
            $data['mobile'] =   I("mobile");
            $data['regtime'] = time();
            $list = UserModel::add(json_encode($data));
            $this->ajaxReturn($list);
        }
		/*用户忘记密码*/
		public function recAct(){
		    $l['mobile']= I("mobile");
            $l['code'] = I('sms_code');
            $checkSms = UserModel::checkSmsCode(json_encode($l));
            if($checkSms['status'] != 1){
               $this->ajaxReturn($checkSms);
            }
		    $data['mobile']= I("mobile");
			$list = UserModel::loginpw(json_encode($data));
            $this->ajaxReturn($list);
			}
		/*用户重置密码*/
        public function setAct(){
            $data['password'] = md5(I('password'));
            $result = UserModel::setpassword(json_encode($data));
            $this->ajaxReturn($result);
		}
		/*商家忘记密码*/
		public function recAct_gz(){
		    $l['mobile']= I("mobile");
            $l['code'] = I('sms_code');
            $checkSms = UserModel::checkSmsCode(json_encode($l));
            if($checkSms['status'] != 1){
               $this->ajaxReturn($checkSms);
            }
		    $data['mobile']= I("mobile");
			$list = UserModel::loginpw_gz(json_encode($data));
            $this->ajaxReturn($list);
			}
		/*商家重置密码*/
        public function setAct_gz(){
            $data['password'] = md5(I('password'));
            $result = UserModel::setpassword_gz(json_encode($data));
            $this->ajaxReturn($result);
		}
        /*用户登录*/
        public function loginAct() {
            $l['mobile']= I("mobile");
            $l['code'] = I('sms_code');
            $checkSms = UserModel::checkSmsCode(json_encode($l));
            if($checkSms['status'] != 1){
               $this->ajaxReturn($checkSms);
            }
            $list = UserModel::login(json_encode($l));
            $this->ajaxReturn($list);

        }

        /*商家注册*/
        public function regStoreAct() {
            $l['mobile']= I("mobile");
            $l['code'] = I('sms_code');
            $checkSms = UserModel::checkSmsCode(json_encode($l));
            if($checkSms['status'] != 1){
               $this->ajaxReturn($checkSms);
            }
            $data['mobile'] =   I("mobile");
            $data['regtime'] = time();
            $data['type'] = I('CheckAll');
            $data['company_name'] =  I("company_name");
            $address = I('address'); 
            $data['city'] = $address['prov'].$address['city'].$address['dist'];
            if($address){
                $data['town'] = $address['city'];  
            }else{
                $data['town'] = I('city');
            }
            $data['realname'] =  I("realname");
            $data['identity'] = intval(I('fenlei'));
            $list = UserModel::addStore(json_encode($data));
            $this->ajaxReturn($list);
        }
            /*商家登录*/
            public function loginStoreAct() {
                $l['mobile']= I("mobile");
                $l['code'] = I('sms_code');
                $checkSms = UserModel::checkSmsCode(json_encode($l));
                if($checkSms['status'] != 1){
                   $this->ajaxReturn($checkSms);
                }
                $list = ForemanModel::login(json_encode($l));
                $this->ajaxReturn($list);
            }
    /* 验证手机号是否存在*/
        public function checkMobile($mobile) {
			$result = UserModel::checkMobile(json_encode($mobile));
          
        }

        public function verifycode(){
            $config =    array(
                'fontSize'    =>   30,    // 验证码字体大小
                'length'      =>    4,     // 验证码位数
                'useNoise'    =>    false, // 关闭验证码杂点
                'useCurve'    =>    false,
            );
            $Verify = new \Think\Verify($config);
            $Verify->codeSet = '0123456789'; 
            $Verify->entry();
        }
        /*上传图片*/
        public function upload() {
            if (!empty($_FILES)) {
                //如果有文件上传 上传附件
                $result= UserModel::_upload();
                if($result){
                    $result['status'] =1;
                    $result['info'] ="上传成功";
                    $this->ajaxReturn($result);
                }else{
                    $result['status'] =0;
                    $result['info'] ="上传失败";
                    $this->ajaxReturn($result);
                }
            }
        }

            /*短信验证码*/
            public function smsCode() {
                $mobile = I('mobile');
                $code = rand(1000,9999);

                $result = SmsModel::sendSmsCode($mobile,$code);
               
                $list = json_decode($result,true);
                if ($list['status'] == 1) {
                    $data['status']  = 'ok';
                    $data['msg'] = '发送成功';
                    $this->ajaxReturn($data);
                } else {
                    $data['status']  = 'error';
                    $data['msg'] = '发送失败';
                    $this->ajaxReturn($data);
                }
            }

              /*检查验证码*/
        public function checkCode(){
            $verifycode = I("verify");
            $verify= $this->check_verify($verifycode, '');
            if($verify == 1) {
                $this->success('验证成功！');
            }else{
                $this->error('数字验证码错误！');
            }
            
        }

        /*用工方添加tag*/
        public function userGetTag(){
            $tag = I('tag');
            $uid = I('uid');
            $user_id = $uid?$uid:$_SESSION['uid'];
            $User = M("User");
            $data['tag'] = $tag;
            $res = $User->where('userid='.$user_id)->save($data);
            if($res){
                $data['status']  = 1;
                $data['msg'] = '保存成功';
                $this->ajaxReturn($data);
            } else {
                $data['status']  = 0;
                $data['msg'] = '保存失败';
                $this->ajaxReturn($data);
            }
        }

        /*施工方添加tag*/
        public function workGetTag(){
            $tag = I('tag');
            $fid = I('fid');
            $fid = $fid?$fid:$_SESSION['fid'];
            $Foreman = M("Foreman");
            $data['tag'] = $tag;
            $res = $Foreman->where('id='.$fid)->save($data);
            if($res){
                $data['status']  = 1;
                $data['msg'] = '保存成功';
                $this->ajaxReturn($data);
            } else {
                $data['status']  = 0;
                $data['msg'] = '保存失败';
                $this->ajaxReturn($data);
            }
        }

}