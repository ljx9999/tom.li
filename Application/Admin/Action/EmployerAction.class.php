<?php
namespace Admin\Action;
/*
 * Created by ICE 2013-09-06
 * 用户管理模块 用于显示会员列表 和管理员在后台进行用户管理
 */
class EmployerAction extends AdminBaseAction {
     /**
     * 首页
     */
    public function index() {
      $sUserKey = $this->thisUserObj['id'];
      S($sUserKey . "_employer", NULL); //点击首页时, 删除缓存条件
      $resultArr = $this->getList();
      $this->assign("userList", $resultArr['list']);
      $this->assign("page", $resultArr['pageShow']);
      $this->assign('last_page',$resultArr['last_page']);
      $emptyList = "暂无信息。";
      $this->assign('empty', $emptyList);

      $this->display();
    }

     /**
       * 取留言列表
       */
      public function getList($whereParam=""){
          $billboardList = array();
          $where = $whereParam;

          $sUserObj = session(C('ADMIN_USER_KEY'));
          $sUserKey = $sUserObj['id'];
          $oldParam = S($sUserKey."_employer");
          $map = array();

          $where['status'] = array("EGT",0);
          $keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:((!empty($oldParam['keyword']))?$oldParam['keyword']:"");   
          if(!empty($keyword)){
              $maps['realname'] = $keyword;
              $maps['mobile'] = $keyword;
              $maps['town'] = $keyword;
              $maps['_logic'] = 'OR';
              $where['_complex'] = $maps;
              $map['keyword'] = $keyword;
          }
        
          if (!empty($sort_by) && !empty($sort_order)) {
              $sort_by    = I("sort_by", "userid");
              $sort_order = I("sort_order", "desc");
              $order      = "`$sort_by` $sort_order ,userid desc";
          } else {
              $sort_by    = isset($_REQUEST['sort_by']) ? $_REQUEST['sort_by'] : "";
              $sort_order = isset($_REQUEST['sort_order']) ? $_REQUEST['sort_order'] : "";
              if ($sort_by) {
                  $order = "`$sort_by` $sort_order ,userid desc";
              } else {
                  $order = "userid desc";
              }
          }

          $p = empty($_GET['p'])?0:$_GET['p'];
          $user = M('user');
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
          S($sUserKey."_employer",$map);
          $show       = $Page->show();// 分页显示输出
         
          $list =  $user->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
          $billboardList['list'] = $list;
          $billboardList['pageObj'] = $Page;
          $billboardList['pageShow'] = $show;
          $billboardList['last_page'] = $last_page;

          return  $billboardList;
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
      $evaluate = M("user");
        if(empty($contentId)){ //数据对象创建错误
            return 0;
        }
        $content['userid'] = $contentId;
        $content['status'] = -1;
        if( $evaluate->save($content)){
            return 1;
        }else{
          return 0;
        }
        
    }


    public function ajaxSearch() {

      $resultArr = $this->getList();
     //dump($resultArr);exit();
      $this->assign("userList", $resultArr['list']);

      $emptyList = "没有符合搜索条件的信息。";
      $this->assign('empty', $emptyList);
      $contentTabelHtml = $this->fetch("Employer/content-table.inc");
      $ajaxResult       = array("tabelBody" => $contentTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
      $this->success($ajaxResult);
    }

     /*
    * 
    *用工方详情
    *
    */
    public function detail() {
      $id = I('id');
      if(!empty($id)){
          $user = M('user');
          $demand = M('demand');
          $where['userid'] = $id;
          $user_list = $user->where($where)->find();
          $wheres['id'] = $user_list['userid'];
          $user_demands = $demand->where($wheres)->select();    
      }
      //dump($user_list );
      $this->assign('user_demands',$user_demands);
      $this->assign('user_list',$user_list);
      $this->display();
    }

    

}