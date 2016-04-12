<?php
/**
 * User: 吉鑫
 * Date: 15-6-17
 * 活动相关Action
 */
namespace Admin\Action;
class ActivityAction extends AdminBaseAction {

	/**
	 * 显示促销活动
	 */
	public function index() {
		// P 从 0 开始
		$p        = ($_GET['p']) ? ($_GET['p']) : 0;
		$activity = M('activity');
		$list     = $activity->order('add_time')->page($p . ',5')->select();
		$this->assign('activities', $list); // 赋值数据集
		$count = $activity->count();// 查询满足要求的总记录数
		$Page  = new \Common\Library\Tieson\PageAjax($count, 5);// 实例化分页类 传入总记录数和每页显示的记录数
        $last_page = ceil($count/5);
		$show  = $Page->show();// 分页显示输出
		$this->assign('page', $show);// 赋值分页输出
        $this->assign('last_page', $last_page);
		$this->display(); // 输出模板
	}

	/**
	 * 增加促销活动
	 */
	public function add() {
       

		$this->display();
	}

	/**
	 * 修改促销活动
	 */
	public function edit() {
		$id = I('id');

		$activityList = $this->getActivityById($id);
        $this->assign('activity', $activityList);
		$this->display();
	}

    /*
     * 根据id获取活动相关的信息
     * */
    public function getActivityById($id){
        $where['id'] = $id;
        $DB_activity = M('activity');
        $activityInfo = $DB_activity->where($where)->find();
        $activityInfo['start_time'] = date("Y-m-d H:i:s",$activityInfo['start_time']);
        $activityInfo['end_time'] = date("Y-m-d H:i:s",$activityInfo['end_time']);
        return $activityInfo;
    }

	/**
	 * 搜索
	 */
	public function searchAct() {

		$keywords     = '%' . I('keyword') . '%';
		$map          = array();
		$map['act_title'] = array('like', $keywords);
		// P 从 0 开始
		$p = ($_GET['p']) ? ($_GET['p']) : 0;

		$activity = M('activity');

		$list = $activity->where($map)->order('add_time')->page($p . ',5')->select();
		$this->assign('activities', $list); // 赋值数据集
		$count = $activity->where($map)->count();// 查询满足要求的总记录数
		$Page  = new  \Common\Library\Tieson\PageAjax($count, 5);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
        $last_page = ceil($count/5);
        $this->assign('last_page', $last_page);
		$this->assign("activities", $list);
		$emptyList = "没有符合搜索条件的促销信息";
		$this->assign('empty', $emptyList);
		$productTabelHtml = $this->fetch("Activity/activity-table.inc");
		$ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $Page->ajaxShow());
		$this->success($ajaxResult);

	}

   
    /*
     * ajax方法获取编辑页面中，、
     * 除去现有商品后的剩余未添加商品列表
     * */

    public function ajaxActivityLastProduct(){
        $activityid = I('activity_id');
        $sUserKey = $this->thisUserObj['id'];
        S($sUserKey . "_productParam", NULL); //删除缓存条件
        $resultArr = $this->getLastProductList($activityid);
        $this->assign("productList", $resultArr['productList']);
        $productTabelHtml = $this->fetch("p_list_search.inc");
        $ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
        $this->success($ajaxResult);

    }

   
    
    /*
     * 获取分类信息
     * */
    public function getCatoryById($id){
        $where['id'] = $id;
        $DB_catory = M('category');
        $catory = $DB_catory->where($where)->find();

        return $catory;
    }
   
    /*
     * 根据product id获取产品相关信息
     * */
    public function getProductInfoById($id){
        $where['id'] = $id;
        $DB_product = M('product');

        $productInfo = $DB_product->where($where)->find();

        return $productInfo;
    }

    /*
     *ajax方法添加促销活动
     * */
    public function ajaxAddActivity(){
        $data['act_title'] = I('act_title');
        $data['status'] = I('status');
        $data['type'] = I('type');
        $data['act_url'] = I('act_url');
        $data['act_description'] = I('act_description');
        $data['add_time'] = time();
        $data['act_order'] = I('act_order');    
        $data['start_time'] = strtotime(I('start_time'));
        $data['end_time'] = strtotime(I('end_time'));
        $data['act_image'] = I('act_image');
        $data['thumb_img'] = I('thumb_img');
        $activity = M('activity');
        $activity->create();
        $res = $activity->add($data);
        if($res){
            $this->success('添加成功',U('index'));
        }else{
            $this->error('添加失败');
        }
    }

    /*
     * ajax编辑促销活动
     * */
    public function ajaxEditActivity(){
        $activityid = I('activity_id');
        $data['act_title'] = I('act_title');
        $data['status'] = I('status');
        $data['act_description'] = I('act_description');
        $data['add_time'] = time();
        $data['act_order'] = I('act_order');    
        $data['start_time'] = strtotime(I('start_time'));
        $data['end_time'] = strtotime(I('end_time'));
        $data['act_image'] = I('act_image');
        $data['act_url'] = I('act_url');
        $data['type'] = I('type');
        $data['thumb_img'] = I('thumb_img');
        $where['id'] = $activityid;
        $DB_activity = M('activity');

        $updRes = $DB_activity->where($where)->save($data);

        if ($updRes > 0) {
            $this->success("编辑促销活动成功", U('index'));
        } else if ($updRes === 0) {
            $this->success("没有修改的信息",'#');
        }else{
            $this->error("编辑促销活动失败，请稍后重试");
        }
    }

    /**
     * 删除优惠券功能
     */
    public function deleteAct() {
        $id       = I('id');
        $discount = M('activity');
        $res = $discount->delete($id);
        if($res !== false){
            echo"succ";
        }else{
            echo "fail";
        }
        
    }

    /**
     * 删除多个优惠券
     */
    public function ajaxMultiDeleteAct() {
        $discount = D('activity');
        $ids      = join(I('activity_ids'), ',');
        $discount->delete($ids);
        if ($discount->getError()) {
            $this->error($ids->getError());
        }
        $this->success('');
    }



  

    
}