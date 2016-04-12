<?php
namespace Admin\Action;
/*
 * Created by lijixin
 * 用户管理模块 
 */
class WorkerAction extends AdminBaseAction {
     /**
     * 首页
     */
    public function index() {
        $sUserKey = $this->thisUserObj['id'];
        S($sUserKey . "_worker", NULL); //点击首页时, 删除缓存条件
        $resultArr = $this->getList();
        $this->assign("userList", $resultArr['list']);
        $this->assign("page", $resultArr['pageShow']);
        $this->assign('last_page',$resultArr['last_page']);
        $emptyList = "暂无信息。";
        $this->assign('empty', $emptyList);
        $this->display();
    }

     /**
       * 取用户列表
       */
      public function getList($whereParam=""){
          $billboardList = array();
          $where = $whereParam;

          $sUserObj = session(C('ADMIN_USER_KEY'));
          $sUserKey = $sUserObj['id'];
          $oldParam = S($sUserKey."_worker");
          $map = array();

          $where['status'] = array("EGT",0);
          $keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:((!empty($oldParam['keyword']))?$oldParam['keyword']:"");   
          if(!empty($keyword)){
              $where['realname|company_name|mobile|town'] = array("LIKE","%$keyword%");
              $map['keyword'] = $keyword;
          }
        
          if (!empty($sort_by) && !empty($sort_order)) {
              $sort_by    = I("sort_by", "id");
              $sort_order = I("sort_order", "desc");
              $order      = "`$sort_by` $sort_order ,id desc";
          } else {
              $sort_by    = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : "";
              $sort_order = isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : "";
              if ($sort_by) {
                  $order = "`$sort_by` $sort_order ,id desc";
              } else {
                  $order = "id desc";
              }
          }

          $p = empty($_GET['p'])?0:$_GET['p'];
          $user = M('foreman');
          $pre_page = 20; //每一页显示多少条数据
          import('@.CN.Tieson.PageAjax'); // 导入分页类
          $count      = $user->where($where)->count();// 查询满足要求的总记录数
          $Page       = new \Common\Library\Tieson\PageAjax($count,$pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
          $last_page = ceil($count/10); 
          //分页跳转的时候保证查询条件
          foreach($map as $key=>$val) {
              $Page->parameter   .=   "$key=".urlencode($val).'&';
          }
          $map['p'] = $p;
          S($sUserKey."_worker",$map);
          $show       = $Page->show();// 分页显示输出
         
          $list =  $user->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
          $billboardList['list'] = $list;
          $billboardList['pageObj'] = $Page;
          $billboardList['pageShow'] = $show;
          $billboardList['last_page'] = $last_page;

          return $billboardList;
      }

      //删除操作
      public function deleteAct() {
          $result = 0;
          if (isset($_REQUEST['id'])) {
              $id = I('id');
              $result += $this->delContent($id);

          } elseif (isset($_REQUEST['ids'])) {
              $ids = I('ids');
              if (!empty($ids)) {
                  foreach ($ids as $key => $id) {
                      $result +=  $this->delContent($id);
                  }
              }
          }
          if ($result > 0) {
              $this->success("删除成功!", U('index'));
          } else {
              $this->error("删除失败");
          }
      }

    /**
     * 删除
     * @param $contentId
     */
    public function delContent($contentId){
        $evaluate = M("foreman");
        if(empty($contentId)){ //数据对象创建错误
            return 0;
        }
        $content['id'] = $contentId;
        $content['status'] = -1;
        if( $evaluate->save($content)){
            return 1;
        }else{
            return 0;
        }
        
    }

    /*
    *
    *
    *搜索功能
    *
    */
    public function ajaxSearch() {
        $resultArr = $this->getList();
        $this->assign("userList", $resultArr['list']);

        $emptyList = "没有符合搜索条件的信息。";
        $this->assign('empty', $emptyList);
        $contentTabelHtml = $this->fetch("Worker/worker-table.inc");
        $ajaxResult       = array("tabelBody" => $contentTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
        $this->success($ajaxResult);
    }

    /*
    * 
    *施工方详情
    *
    */
    public function detail() {
      $id = I('id');
      if(!empty($id)){
          $worker = M('foreman');
          // $foreman = M('foreman_detail');
          $case = M('case');
          $where['id'] = $id;
          $worker_list = $worker->where($where)->find();
          // $wheres['foreman_id'] = $worker_list['id'];
          // $worker_detail = $foreman->where($wheres)->find();
          // $worker_list['worker_detail'] = $worker_detail; 
          $case_where['parentid'] = $id; 
          $case_list = $case->where($where)->select();
          
      }
      //dump($worker_list );
      $this->assign('worker_list',$worker_list);
      $this->assign('case_listt',$case_list);
      $this->display();
    }

  /*
  *
  *
  *审核资质操作
  * 
  */
  public function isDone(){
      $id = I('id');
      if(!empty($id)){
          $worker = M('foreman');
          $where['id'] = $id;
          $data['status'] = 10;
          $res = $worker->where($where)->save($data);
      }
      if($res){
          $this->success('审核成功！');
      }else{
          $this->error('审核失败！');
      }
  }

  public function edit(){
      $id = I('id');
      if(!empty($id)){
          $worker = M('foreman');
          $where['id'] = $id;
          $worker_list = $worker->where($where)->find();    
      }
      $this->assign('worker_list',$worker_list);
      
      $this->display();
  }

  /*
     *修改编辑
     * */
    public function editAct(){
        $data['realname'] = I('realname');
        $data['is_real'] = I('is_real');
        $data['status'] = I('status');
        $data['is_discout'] = I('is_discout');
        $data['is_recommend'] = I('is_recommend');
        $data['is_security'] = I('is_security');
        $data['type'] = I('type');
        $data['age'] = I('age');
        $data['mobile'] = I('mobile');
        $data['msg'] = I('msg');    
        $data['identity'] =I('identity');
        $data['work_life'] = I('work_life');
        $data['address'] = I('address');
        $data['id_positive_img'] = I('id_positive_img');
        $data['id_negative_img'] = I('id_negative_img');
        $data['head_image'] = I('head_image');
        $wheres['id'] = I('id');
        $feedback = M('foreman');
        $res = $feedback->where($wheres)->save($data);
        if($res){
            $this->success('编辑成功',U('index'));
        }else{
            $this->error('编辑失败');
        }
    }

    

}