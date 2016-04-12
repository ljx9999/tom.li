<?php
namespace Admin\Action;
/**
 * Created by lijixin
 * 订单管理
 */
class OrderAction extends AdminBaseAction {

	private $adminId = "";

	public function __construct() {
		parent::__construct();

		$admin = session(C('ADMIN_USER_KEY'));

		$this->adminId = $admin['id'];
	}


	public function index() {
		$sUserKey = $this->thisUserObj['id'];
		S($sUserKey . "_orderParam", NULL); //点击首页时, 删除缓存条件


		$result = $this->getOrderList();
		$this->assign('orderList', $result['list']);
		$this->assign('page', $result['pageShow']);
		$emptyList = "暂无订单信息";
		$this->assign('empty', $emptyList);
		$this->display();
	}

	public function ajaxSearch() {
		$resultArr = $this->getOrderList();
		$this->assign("orderList", $resultArr['list']);
		$emptyList = "没有符合搜索条件的订单信息。";
		$this->assign('empty', $emptyList);

		$productTabelHtml = $this->fetch("Order/order-table.inc");
		$ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());

		$this->success($ajaxResult);
	}

	 /**
       * 取订单列表
       */
    public function getOrderList($whereParam=""){
	    $billboardList = array();
	    $where = $whereParam;

	    $sUserObj = session(C('ADMIN_USER_KEY'));
	    $sUserKey = $sUserObj['id'];
	    $oldParam = S($sUserKey."_order");
	    $map = array();

	  	$where['status'] = array("EGT",0);
	 	$keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:((!empty($oldParam['keyword']))?$oldParam['keyword']:"");   
	  	if(!empty($keyword)){
	      	$where['title|order_number'] = array("LIKE","%$keyword%");
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
		$orders = D('Order');
      	$pre_page = 10; //每一页显示多少条数据
      	import('@.CN.Tieson.PageAjax'); // 导入分页类
      	$count      = $orders->where($where)->count();// 查询满足要求的总记录数
      	$Page       = new \Common\Library\Tieson\PageAjax($count,$pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
      	$last_page = ceil($count/10); 
      	//分页跳转的时候保证查询条件
      	foreach($map as $key=>$val) {
          	$Page->parameter   .=   "$key=".urlencode($val).'&';
      	}
      	$map['p'] = $p;
      	S($sUserKey."_orderParam",$map);
     	$show       = $Page->show();// 分页显示输出
     	$users = M('user');
      	$list =  $orders->relation(true)->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
     	foreach ($list as $key => $val) {
     		$user_name = $users->where('userid='.$val['userid'])->getField('realname');
     		$list[$key]['userName'] = $user_name;
     	}
      	$billboardList['list'] = $list;
      	$billboardList['pageObj'] = $Page;
      	$billboardList['pageShow'] = $show;
      	$billboardList['last_page'] = $last_page;

      	return $billboardList;
    }


	public function deleteAct() {
		$id  = I("id", 0);
		$ids = I("ids", 0);

		if ($id > 0) {
			$orderObj = $this->getOrderInfoById($id);
			if (!empty($orderObj)) {

				$orderModel           = M("order_info");
				$info['order_status'] = -1;

				$result = $orderModel->where('id=' . $id)->save($info);
				$this->success('订单删除成功');
			}
			$this->error("订单信息未同步, 请刷新重试!");
		}
		if (count($ids) > 0) {

			$delNumber = 0;
			foreach ($ids as $id) {
				$orderObj = $this->getOrderInfoById($id);
				if (!empty($orderObj)) {
					$orderModel           = M("order_info");
					$info['order_status'] = -1;
					$result = $orderModel->where('id=' . $id)->save($info);
					if ($result) {
						$delNumber += $result;
					}
				}
			}
			if ($delNumber > 0) {
				$this->success('计 ' . $delNumber . ' 个订单删除成功');
			}
			$this->error("订单信息未同步, 请刷新重试!");
		}

		$this->error("未找到要删除的订单信息");

	}
	
    /*
    * 
    *施工方详情
    *
    */
    public function info() {
      $id = I('id');
      $userName = I('userName');
      if(!empty($id)){
          $orders = D('order');
          $demand = M('demand');
          $where['id'] = $id;
          $order_list = $orders->where($where)->find();
          $order_list['earnest'] = number_format(($order_list['quote'] * 0.3),2);
          $wheres['id'] = $order_list['demand_id'];
          $order_detail = $demand->where($wheres)->find();
          $order_list['order_detail'] = $order_detail;     
          $order_list['userName'] = $userName; 
      }
      //dump($order_list );
      $this->assign('order_list',$order_list);

      $this->display();
    }

     /*
    * 
    *施工方详情
    *
    */
    public function cancelInfo() {
      $id = I('id');
      $userName = I('userName');
      if(!empty($id)){
          $orders = D('order');
          $demand = M('demand');
          $where['id'] = $id;
          $order_list = $orders->where($where)->find();
          $order_list['earnest'] = number_format(($order_list['quote'] * 0.3),2);
          $wheres['id'] = $order_list['demand_id'];
          $foreman = M('foreman');
          $worker_name = $foreman->where('id ='.$order_list['fid'])->getField('realname');
          $order_detail = $demand->where($wheres)->find();
          $order_list['order_detail'] = $order_detail;     
          $cancel_order = M('cancel_order');
          $cancelInfo = $cancel_order->where('order_id = '.$id)->getField('cancel_info');
          $order_list['cancel_info'] = $cancelInfo;
          $order_list['worker_name'] = $worker_name;

      }
      //dump($order_list);
      $this->assign('order_list',$order_list);

      $this->display();
    }

    public function ajaxCancelOrder(){
        $id = I('order_id');
        $where['id'] = $id;
        $order = M('order');
        $data['status'] = 35;
        $res = $order->where($where)->save($data);
        $cancel_order = M('cancel_order');
        $map['order_id'] = $id;
        $datas['status'] = 1;
        $rell = $cancel_order->where($map)->save($datas);
        if($res && $rell){
            $this->success('审核成功！');
        }else{
            $this->error('审核失败！');
        }
    }

    public function changeStatus(){
      $id = I('id');
      $order = M('order');
      $where['id'] = $id;
      $order_list = $order->where($where)->find();
      if($order_list['status'] == 5){
        $data['status'] = 10;
      }else if($order_list['status'] == 15){
         $data['status'] = 20;
      }else if($order_list['status'] == 20){
         $data['status'] = 25;
      }
      $res = $order->where($where)->save($data);
      if($res){
          $this->success('修改成功');
      }else{
          $this->error('修改失败！');
      }

    }


   
}