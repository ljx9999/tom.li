<?php
namespace Admin\Action;
use Think\Action;
/**
 * Created by Sublime Text
 * User: lijixin
 * 
 */
class LoginAction extends Action
{

    public function __construct(){
        parent::__construct();

        Load('extend');//调取扩展函数库
        $this->assign("css_dir", __APP__."/Public/css");
        $this->assign("js_dir",__APP__."/Public/js");
        $this->assign("images_dir",__APP__."/Public/images");

        $admin = session(C('ADMIN_USER_KEY'));
        $admin['client_ip'] = long2ip($admin['last_ip']);
        $this->assign('admin',$admin);
    }


    /**
     * 登陆页面
     */
    public function login()
    {

    /*
     * ICE 2013-07-05
     * 实现自动记录登陆信息功能
     * 显示登陆页面前 先读取SESSEION信息 然后写入页面
     */
        $userInfo = cookie(C('ADMIN_USER_KEY'));

        $refer = I('refer');

        //获得COOKIE中存储的用户名
        //dump ($userInfo);
        $this->assign('userInfo',$userInfo);
        $this->assign('refer',$refer);
        $this->display('User/signin');
    }

    /**
     * 登陆验证
     */
    public function userLogin()
    {
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];
        $remberme = $_REQUEST['autoSignIn'];

        $password = md5(md5($password));
        $adminModel = M('admin_user');

        $admin = $adminModel->where(array('username' => $username))->find();

        if (!empty($admin)) {
            if ($admin['password'] == $password && $admin['admin_status'] > -1) {
                    $admin['last_time'] = date("Y-m-d h:i:s");
                    $admin['last_ip'] = get_client_ip(1);
                    //记录IP和上次登录时间
                    $adminModel->save($admin);
                    $admin['remberme'] = $remberme;
                    $this->cookieAndSession($admin);
                    if ($admin['admin_status'] > 0) { //有效管理员
                        $this->success(array('refer' => U('/Admin/Employer/index')));
                    } else {
                        $this->success('您的管理员帐号未激活,请修改密码密码激活',array('refer' => U('/Admin/Index')));
                    }
            } else {
                $this->error('用户不存在或密码不正确');
            }
        } else {
            $this->error('用户不存在或密码不正确');
        }
    }

    private function cookieAndSession($user)
    {
        session(C('ADMIN_USER_KEY'), $user);
        if($user['remberme']){
            cookie(C('ADMIN_USER_KEY'), array('id' => $user['id'], 'username' => $user['username'], 'last_time' => time() , 'remberme' => $user['remberme']));
        }else{
            cookie(C('ADMIN_USER_KEY'),null);

        }
    }
    /**
     * logout
     */
    public function logout()
    {
        session(C('ADMIN_USER_KEY'), null);
        cookie(C('ADMIN_USER_KEY'), null);
        $this->redirect('Login/login');
    }

    public function adminSettingPassword(){
        $loginAdmin = session(C('ADMIN_USER_KEY'));

        if($loginAdmin['admin_status'] < 1){
            $this->assign("admin_status",1);
        }

        $this->display('User/admin_change_password');
    }

    public function ajaxCheckOldPassword(){
        $old_password = I("old_password",'');

        if(empty($old_password)){
            die("false");
        }

        $loginAdmin = session(C('ADMIN_USER_KEY'));

        $id = $loginAdmin['id'];

        $adminModel = M('admin_user');

        $passwordObj = $adminModel->find($id);

        if($passwordObj['password'] == md5(md5($old_password))){
            die("true");
        }
        die("false");

    }

    /*
    * ICE 2013-06-26
    * 修改密码 返回修改结果 success or false
    *
    */
    public function changePassword () {


        $password = I("password","");

        $confirm_password = I("confirm_password","");

        if ($password != $confirm_password) {
            $this->error('两次输入的新密码不一样');
        }

        $loginAdmin = session(C('ADMIN_USER_KEY'));

        $id = $loginAdmin['id'];
        $adminModel = M("admin_user");


        $adminObj['password'] = md5(md5($password));
        $adminObj['admin_status'] = 1;

        if($adminModel->where('id='.$id)->save($adminObj)){
            $this->success('修改密码成功,请重新登陆!');
        }

        $this->error('修改密码失败');


    }


}
