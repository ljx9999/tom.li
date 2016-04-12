<?php
/**
 * Created by Sublime Text
 * User: lijixin
 * 
 */

namespace Admin\Action;
import('@.Model.Admin.AuthRuleModel');
import('@.Model.Admin.AuthGroupModel');
class AuthManagerAction extends AdminBaseAction{



    public function index(){
        $result  = $this->getAdminGroupList(array('module'=>'admin'),'id asc');
        $list = $this->int_to_string($result['groupList']);
        $this->assign('authList', $list );
        $this->assign('last_page', $result['last_page'] );
        $this->assign('empty','暂无管理员信息');
        $this->assign('page',$result['pageShow']);
        $this->display('User/auth_group');
    }


    public function deleteAct(){
        $id = I("id",0);
        $ids = I("ids",0);

        $authGroupModel       =  M('AuthGroup');

        if($id > 0){
            $authObj = $authGroupModel->find($id);
            if(!empty($authObj)){

                $auth['status'] = -1;

                $result = $authGroupModel->where('id='.$id)->save($auth);
                if($result){
                    $this->success('角色删除成功');
                }
            }
            $this->error("角色信息未同步, 请刷新重试!");
        }
        if(count($ids) > 0){

            $delNumber = 0;
            foreach($ids as $id){
                $authObj = $authGroupModel->find($id);
                if(!empty($authObj)){

                    $auth['status'] = -1;

                    $result = $authGroupModel->where('id='.$id)->save($auth);
                    if($result){
                        $delNumber += $result;
                    }
                }

            }
            if($delNumber > 0){
                $this->success('计 '.$delNumber.' 个角色删除成功');
            }
            $this->error("角色信息未同步, 请刷新重试!");
        }
        $this->error("未找到要删除的角色信息");
    }

    
    public function ajaxSearch($countNum=0) {
    /*
     *  ajax 方式获得模糊搜索结果
     */
    $result  = $this->getAdminGroupList(array('module'=>'admin'),'id asc');
    $this->assign("authList",$result['groupList']);
    $this->assign('empty','没有符合搜索条件的信息');
    $productTabelHtml   = $this->fetch("User/auth_group_table.inc");
    $ajaxResult = array("tabelBody"=>$productTabelHtml ,'pageBody'=>$result['pageObj']->ajaxShow());
    $this->success($ajaxResult);

    }
    
    
    public function changeStatusAct(){
        $id = I("id",0);
        $status = I("status","");

        if($id > 0){
            $authObj['id'] = $id;
            $authObj['status'] = $status;
            $authGroupModel       =  M('AuthGroup');
            $r = $authGroupModel->save($authObj);
            if($r===false){
                $this->error('操作失败'.$authGroupModel->getError());
            } else{
                $this->success('操作成功!');
            }
        }
    }

    public function groupAdd(){
        $this->display('User/auth_group_add');
    }
    public function groupEdit(){

        $id = I("id",0);

        if($id > 0){
            $authGroup = M("auth_group")->find($id);
            $this->assign("authGroup",$authGroup);
            $this->display('User/auth_group_edit');
        }else{
            $this->display('User/auth_group_add');
        }


    }

    public function authAddAct(){
        $authObj = array();

        $authObj['title'] = I("title","");
        $authObj['module'] = 'admin';
        $authObj['type'] = 1; //AuthGroupModel::TYPE_ADMIN 此处原为这样 但因为AuthGroupModel 无TYPE_ADMIN这个属性或方法 暂且改为常量待日后改正
        $authObj['description'] = I("description","");
        $authObj['status'] = I("status","");
        $authGroupModel       =  M('AuthGroup');

        $r = $authGroupModel->add($authObj);
        if($r===false){
            $this->error('添加失败'.$authGroupModel->getError());
        } else{
            $this->success('添加成功');
        }
    }
    public function authEditAct(){
        $authObj = array();

        $authObj['id'] = I("id","");
        $authObj['title'] = I("title","");
        $authObj['module'] = 'admin';
        $authObj['type'] = D('AuthGroup')->TYPE_ADMIN;
        $authObj['description'] = I("description","");
        $authObj['status'] = I("status","");
        $authGroupModel       =  M('AuthGroup');

        $r = $authGroupModel->save($authObj);

        if ($r > 0) {
            $this->success("修改成功", U('index'));
        } else if ($r === 0) {
            $this->success("没有修改的信息",'#');
        }else{
            $this->error("修改失败");
        }
    }

    public function ajaxCheckAuthTitle(){
        $title = I('title','');

        $authModel = M("auth_group");
        $where = array();
        if(empty($title)){
            die("true");
        }

        $where['title'] = trim($title);

        $authObj = $authModel->where($where)->find();

        if(empty($authObj)){
            die("true");
        }
        die("false");
    }


    public function access(){

        $groupId = I("id",0);


        $this->updateRules();

        $auth_group = M('AuthGroup')->where( array('status'=>array('egt','0'),'module'=>'admin','type'=>D('AuthGroup')->TYPE_ADMIN) )
            ->getfield('id,id,title,rules');
        $node_list   = $this->returnNodes();
//print_r($node_list);
        $map         = array('module'=>'admin','type'=>D('AuthRule')->RULE_MAIN,'status'=>1);
        $main_rules  = M('AuthRule')->where($map)->getField('name,id');
        $map         = array('module'=>'admin','type'=>D('AuthRule')->RULE_URL,'status'=>1);
        $child_rules = M('AuthRule')->where($map)->getField('name,id');
//print_r($child_rules);
        $this->assign('main_rules', $main_rules);
        $this->assign('auth_rules', $child_rules);
        $this->assign('node_list',  $node_list);
        $this->assign('auth_group', $auth_group);
        $this->assign('this_group', $auth_group[(int)$groupId]);
        $this->display('User/role_access');
    }

    public function addGroupRules(){

        $rules = $_REQUEST['rules'];

        if(!empty($rules)){
            sort($rules);
            $rules  = trim( implode( ',' , array_unique($rules)) , ',' );
        }

        $authObj['id'] = I("id","");
        $authObj['rules'] = $rules;
        $authGroupModel       =  M('AuthGroup');

        $r = $authGroupModel->save($authObj);
        if($r===false){
            $this->error('操作失败'.$authGroupModel->getError());
        } else{
            $this->success('操作成功!');
        }


    }

    /**
     * 后台节点配置的url作为规则存入auth_rule
     * 执行新节点的插入,已有节点的更新,无效规则的删除三项任务
     */
    public function updateRules(){
        //需要新增的节点必然位于$nodes
        $nodes    = $this->returnNodes(false);
        $AuthRule = M('AuthRule');
        $map      = array('module'=>'admin','type'=>array('in','1,2'));//status全部取出,以进行更新
        //需要更新和删除的节点必然位于$rules
        $rules    = $AuthRule->where($map)->order('name')->select();

        //构建insert数据
        $data     = array();//保存需要插入和更新的新节点
        foreach ($nodes as $value){
            $temp['name']   = strtolower($value['url']);
            $temp['title']  = $value['title'];
            $temp['module'] = 'admin';
            if(isset($value['controllers'])){
                $temp['type'] = D('AuthRule')->RULE_MAIN;
            }else{
                $temp['type'] = D('AuthRule')->RULE_URL;
            }
            $temp['status']   = 1;
            $data[strtolower($temp['name'].$temp['module'].$temp['type'])] = $temp;//去除重复项
        }
        $update = array();//保存需要更新的节点
        $ids    = array();//保存需要删除的节点的id
        foreach ($rules as $index=>$rule){
            $key = strtolower($rule['name'].$rule['module'].$rule['type']);
            if ( isset($data[$key]) ) {//如果数据库中的规则与配置的节点匹配,说明是需要更新的节点
                $data[$key]['id'] = $rule['id'];//为需要更新的节点补充id值
                $update[] = $data[$key];
                unset($data[$key]);
                unset($rules[$index]);
                unset($rule['condition']);
                $diff[$rule['id']]=$rule;
            }elseif($rule['status']==1){
                $ids[] = $rule['id'];
            }
        }
        if ( count($update) ) {
            foreach ($update as $k=>$row){
                if ( $row!=$diff[$row['id']] ) {
                    $AuthRule->where(array('id'=>$row['id']))->save($row);
                }
            }
        }
        if ( count($ids) ) {
            $AuthRule->where( array( 'id'=>array('IN',implode(',',$ids)) ) )->save(array('status'=>-1));
            //删除规则是否需要从每个用户组的访问授权表中移除该规则?
        }
        if( count($data) ){
            $AuthRule->addAll(array_values($data));
        }
        if ( $AuthRule->getDbError() ) {
            trace('['.__METHOD__.']:'.$AuthRule->getDbError());
            return false;
        }else{
            return true;
        }
    }

    private function getAdminGroupList($whereP = "" , $orderStr="" ,$pre_page = 10){
        $keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:((!empty($oldParam['keyword']))?$oldParam['keyword']:"");
        $where = $whereP; //附加查询条件
        $map = array();
        $where['status'] = array('gt',-1);
        /*$title = I('title');
        if(!empty($title)) {
            $where['title'] = $title;
            $map['title'] = $title;
        }
        $status = I('status');
        if(!empty($status)) {
            $where['status'] = $status;
            $map['status'] = $status;
        }*/
        
        if(!empty($keyword)){
            $keywords = '%'.$keyword.'%';
            $where = array();
            $where['module'] = array('like',$keywords);
            $where['title'] = array('like',$keywords);
            $where['description'] = array('like',$keywords);
            $where['_logic'] = "OR";
        }
        $authModel = M("auth_group");
        $order = (empty($orderStr))?" id asc ":$orderStr;

        import('@.Tieson.PageAjax'); // 导入分页类

        $count      = $authModel->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Common\Library\Tieson\PageAjax($count,$pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
        $last_page = ceil($count/$pre_page);
        //分页跳转的时候保证查询条件
        foreach($_POST as $key=>$val) {
            $Page->parameter   .=   "$key=".urlencode($val).'&';
        }
        $show       = $Page->show();// 分页显示输出
        
        $userList = $authModel->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $resultArr['groupList'] = $userList;
        $resultArr['pageObj'] = $Page;
        $resultArr['pageShow'] = $show;
        $resultArr['sql'] =$authModel->getLastSql() ;
        $resultArr['last_page'] = $last_page;

        return $resultArr;
    }


    private function int_to_string(&$data,$map=array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',2=>'未审核',3=>'草稿'))) {
        $data = (array)$data;
        foreach ($data as $key => $row){
            foreach ($map as $col=>$pair){
                if(isset($row[$col]) && isset($pair[$row[$col]])){
                    $data[$key][$col.'_text'] = $pair[$row[$col]];
                }
            }
        }
        return $data;
    }
}