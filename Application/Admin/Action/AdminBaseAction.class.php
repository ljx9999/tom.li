<?php
/**
 * Created by Sublime Text
 * User: lijixin
 * 后台Action 基类，用于过滤登录等操作
 */

namespace Admin\Action;
use Think\Action;
use Think\Controller;

import('@.Model.Admin.AuthRuleModel');
import("ORG.Util.Auth");
class AdminBaseAction extends Controller
{
    protected $thisUserObj;

    public function __construct(){
        parent::__construct();
        $this->addToBack();

        Load('extend');//调取扩展函数库

        $this->assign("css_dir", __APP__."/Public/css");
        $this->assign("js_dir",__APP__."/Public/js");
        $this->assign("images_dir",__APP__."/Public/images");
        $this->assign("uploads_dir",__APP__."/Uploads");
        if(strtoupper(ACTION_NAME) == strtoupper('incompatible')){
            $this->display('Public/incompatible');
            die();
        }

        //验证登陆
        if (! $this->validateLogin()){
            $this->redirect('Login/Login');
        }

        $admin = session(C('ADMIN_USER_KEY'));

        if($admin['admin_status'] < 1){
            $this->redirect('Login/adminSettingPassword');
        }

        $admin['client_ip'] = long2ip($admin['last_ip']);
        $this->assign('admin',$admin);

        // 获取当前用户ID
        define('UID',$admin['id']);
        define('IS_ROOT',   is_administrator());
        $access =   $this->accessControl();
        if ( $access === false ) {
            $this->error('403:禁止访问');
        }elseif( $access === null ){

            $actionStr = ACTION_NAME;
            $modelStr = CONTROLLER_NAME;

            if(array_search(strtolower($modelStr."/".$actionStr),C('RELATION_RULE'),false) !==false){ //手动权限关系设置

                $actionStr = 'index';
            }
            elseif(stripos($actionStr, "ajax") !== false){  //ajax验证及异步方式使用index权限
                $actionStr = 'index';

            }
            elseif(stripos($actionStr, "Act") !== false){ // act请求权限处理
                $actionStr = str_ireplace("Act",'',$actionStr);
            }

            if(!empty($actionStr)){
                $modelStr .= "/";
            }
            $rule  = strtolower($modelStr.$actionStr);

            if ( !$this->checkRule($rule,array('in','1,2')) ){
                $this->error('提示:无权访问,您可能需要联系管理员为您授权!');
            }
        }

        $this->thisUserObj = session(C('ADMIN_USER_KEY'));
    }

    /**
     * 验证是否登陆
     */
    private function validateLogin(){
        $user = session(C('ADMIN_USER_KEY'));
        if ( !$user ){
            $cookie_user = cookie(C('ADMIN_USER_KEY'));
            $rember_me = $cookie_user['remberme'];
            $username = $cookie_user['username'];
            if ($rember_me){
                $memberModel = M('admin_user');
                $member = $memberModel->where(array('username' => $username))->find();
                $member['remberme'] = '1' ;
                $this->cookieAndSession($member);
                return true;
            }
            return false;
        } else {
            if ( $user['username'] == 'admin' ){
                $this->assign('superAdmin',1);
            }
            return true;
        }


    }

    private function cookieAndSession($user)
    {
        session(C('ADMIN_USER_KEY'), $user);
        cookie(C('ADMIN_USER_KEY'), array('id' => $user['id'], 'username' => $user['username'], 'last_time' => time() , 'remberme' => $user['remberme'] ));
    }


    /**
     * action访问控制,在 **登陆成功** 后执行的第一项权限检测任务
     *
     * @return boolean|null  返回值必须使用 `===` 进行判断
     *
     *   返回 **false**, 不允许任何人访问(超管除外)
     *   返回 **true**, 允许任何管理员访问,无需执行节点权限检测
     *   返回 **null**, 需要继续执行节点权限检测决定是否允许访问
     * @author chenyunlong@tieson.cn
     */
    final protected function accessControl(){
        if(IS_ROOT){
            return true;//超级管理员允许访问任何页面
        }
        return null;//需要检测节点权限
    }


    final protected function checkRule($rule, $type=AuthRuleModel::RULE_URL, $mode='url'){
        if(IS_ROOT){
            return true;//管理员允许访问任何页面
        }
        static $Auth    =   null;
        if (!$Auth) {
            $Auth       =   new \Org\Util\Auth();
        }


        if(!$Auth->check($rule,UID,$type,$mode)){
            return false;
        }
        return true;
    }
    

    

    /**
     * 设置后退栈信息
     */
    final protected function addToBack(){

        $checkBackAction = array("INDEX","LIST","AJAXSEARCH");

        $backUrls = session("backUrlStack");

        if(empty($backUrls)){
            $indexUrl = U("index");
            $backUrls = array($indexUrl);
        }

        $modelName = CONTROLLER_NAME;
        $actionName = ACTION_NAME;

        if(!in_array(strtoupper($actionName),$checkBackAction)){
            return ;
        }


        if(IS_AJAX){
            $urlStr = "$modelName/index";
        }else{
            $urlStr = "$modelName/$actionName";
        }


        $params = $_REQUEST;

        unset($params['_URL_']);
        $backUrl = U($urlStr,$params);

        $backKey = array_search($backUrl,$backUrls);
        if($backKey !== false){
            unset($backUrls[$backKey]);
        }

        array_push($backUrls,$backUrl);

        if(count($backUrls) > 6){
            $backUrlsTmp = array_chunk($backUrls,5);
            $backUrls = $backUrlsTmp[1];
        }

        session("backUrlStack",$backUrls);
    }

    //后退操作;
    public function goBack(){

        $backIndex = I('backIndex',0);

        $backUrls = session("backUrlStack");
        $backurl = '';
        for($i=0;$i<$backIndex;$i++){
            $backurl = array_pop($backUrls);
        }
        redirect($backurl, 0, '页面跳转中...');
    }
}


