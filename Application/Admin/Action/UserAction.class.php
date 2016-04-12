<?php
namespace Admin\Action;
/**
 * Class UserAction
 *
 * mackcyl
 */
class UserAction extends AdminBaseAction
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


    public function index(){

        $adminList = $this->getAdminList();

        $this->assign("adminList",$adminList);
        $this->display();
    }

    public function ajaxSearch(){
        $adminList = $this->getAdminList();

        $this->assign("adminList",$adminList);

        $userTabelHtml   = $this->fetch("User/admin-table.inc");
        $ajaxResult = array("tabelBody"=>$userTabelHtml ,'pageBody'=> '');
        $this->success($ajaxResult);
    }

    public function add(){
        $this->display();
    }


    public function group(){
        $uid            =   I('id');
        $auth_groups    =   D('AuthGroup')->getGroups();
        $user_groups    =   D('AuthGroup')->getUserGroup($uid);

        $ids = array();
        foreach ($user_groups as $value){
            $ids[]      =   $value['group_id'];
        }
        $this->assign('auth_groups',$auth_groups);
        $this->assign('uid',$uid);
        $this->assign('user_groups',implode(',',$ids));
        $this->display('user_group');
    }

    /**
     * 将用户添加到用户组,入参uid,group_id
     */
    public function addToGroup(){
        $uid = I('uid');
        $gid = I('group_id');
        if( empty($uid) ){
            $this->error('参数有误');
        }
        $AuthGroup = D('AuthGroup');
        if(is_numeric($uid)){
            if ( is_administrator($uid) ) {
                $this->error('该用户为超级管理员');
            }
            if( !M('admin_user')->where(array('id'=>$uid))->find() ){
                $this->error('管理员用户不存在');
            }
        }

        if( $gid && !$AuthGroup->checkGroupId($gid)){
            $this->error($AuthGroup->error);
        }
        if ( $AuthGroup->addToGroup($uid,$gid) ){
            $this->success('操作成功');
        }else{
            $this->error($AuthGroup->getError());
        }
    }


    public function adminSettingPassword(){
        $loginAdmin = session(C('ADMIN_USER_KEY'));

        if($loginAdmin['admin_status'] < 1){
            $this->assign("admin_status",1);
        }

        $this->display('admin_change_password');
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
    public function addAct(){

        $adminObj = array();

        $userName = I("username","");
        $password = I("password","");
        $confirm_password = I("confirm_password","");
        $email = I("email","");
        $china_name = I("china_name","");

        $adminObj['username'] = $userName;
        $adminObj['password'] = md5(md5($password));
        $adminObj['email'] = $email;
        $adminObj['china_name'] = $china_name;
        $adminObj['create_time'] = date('Y-m-d h:i:s');
        $adminObj['admin_status'] = 1;
        $adminObj['admin_type'] = 1;


        $adminModel = M('admin_user');

        if($adminModel->add($adminObj)){
            $this->success('管理员添加成功');
        }

        $this->error('管理员添加失败');

    }

    public function edit(){

        $id = I("id");


        $userObj = '';

        if(!empty($id)){
            $userObj = $this->getAdminInfo($id);
        }

        $this->assign('userObj',$userObj);
        $this->display();

    }

    /**
     * 管理员信息编辑
     */
    public function editAct(){
        $id = I("id");
        $email = I("email","");
        $china_name = I("china_name","");

        $adminObj['email'] = $email;
        $adminObj['china_name'] = $china_name;

        $adminModel = M('admin_user');

        try{
            if($adminModel->where('id='.$id)->save($adminObj)){
                $this->success('管理员信息修改成功',U('index'));
            }else{
                $this->success('管理员信息未改变','#');
            }
        }catch (Exception $e){
            $this->error('管理员修改失败');
        }


    }

    /**
     * 重置管理员密码
     */
    public function ajaxResetPassword(){
        $id = I("id");

        if($id < 1){
            $this->error("操作失败, 未找到指定管理员");
        }
        $adminModel = M('admin_user');

        $adminObj['password'] = md5(md5('admin'));
        $adminObj['admin_status'] = 0;

        try{
            if($adminModel->where('id='.$id)->save($adminObj)){
                $this->success('管理员密码重置成功,请马上登陆修改密码并进行激活!');
            }else{
                $this->success('管理员密码重置成功,请马上登陆修改密码并进行激活!');
            }
        }catch (Exception $e){
            $this->error('管理员密码重置失败');
        }



    }

    /**
     * 验证管理员登陆名是否已经存在
     */
    public function ajaxCheckUserName(){
        $userName = I('username','');

        $adminModel = M("admin_user");
        $where = array();
        if(empty($userName)){
            die("true");
        }

        $where['username'] = trim($userName);
        $where['admin_status'] = 1;
        $userObj = $adminModel->where($where)->find();

        if(empty($userObj)){
            die("true");
        }
        die("false");
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

        $refer = $this->_get('refer');

        //获得COOKIE中存储的用户名
        //dump ($userInfo);
        $this->assign('userInfo',$userInfo);
        $this->assign('refer',$refer);
        $this->display('signin');
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
            if ($admin['password'] == $password) {

                    $admin['last_time'] = date("Y-m-d h:i:s");
                    $admin['last_ip'] = get_client_ip(1);
                    //记录IP和上次登录时间
                    $adminModel->save($admin);
                    $admin['remberme'] = $remberme;
                    $this->cookieAndSession($admin);
                    if ($admin['admin_status'] > 0) { //有效管理员
                        $this->success(array('refer' => U('/Admin/Index')));
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

    public function deleteAct(){
        $result = 0;

        if(isset($_REQUEST['id'])){
            $user_id = I('id');
            $result += D("user")->del($user_id);

        }elseif(isset($_REQUEST['ids'])){
            $user_ids = I('ids');
            if(!empty($user_ids)){
                foreach($user_ids as $key=>$user_id){
                    $result += D("user")->del($user_id);
                }
            }
        }
        if($result > 0){
            $this->success("删除成功!", U('index'));
        }else{
            $this->error("删除失败");
        }
    }

    /**
     * logout
     */
    public function logout()
    {
        session(C('ADMIN_USER_KEY'), null);
        cookie(C('ADMIN_USER_KEY'), null);
        $this->redirect('User/login');
    }

    private function getAdminList(){
        $adminModel = M("admin_user");

        $where = array();
        $userName = I("username","");

        $keyword = I('keyword');
        $where['admin_status'] = array('gt',-1);
        if(!empty($userName)){
            $where['username'] = $userName;
        }

        if(!empty($keyword)){
            $where['username|email|china_name'] = array("LIKE","%$keyword%");
        }
        $adminList = $adminModel->where($where)->select();

        foreach($adminList as $key=>$value){

            $adminList[$key]['last_ip_str'] = long2ip($value['last_ip']);

        }

        return $adminList;

    }

    private function getAdminInfo($id){

        $adminModel = M("admin_user");

        $adminObj  = $adminModel->find($id);
        $adminObj['last_ip_str'] = long2ip($adminObj['last_ip']);

        return $adminObj;
    }


}
