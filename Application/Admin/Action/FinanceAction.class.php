<?php
/**
 * User: 吉鑫
 * Date: 15-6-17
 * 活动相关Action
 */
namespace Admin\Action;
class FinanceAction extends AdminBaseAction {

	/**
	 * 显示提现申请列表
	 */
	public function index() {
		// P 从 0 开始
		$p        = ($_GET['p']) ? ($_GET['p']) : 0;
		$cash = M('cash');
		$list     = $cash->order('add_time')->page($p . ',10')->select();
        foreach ($list as $key => $val) {
            $list[$key]['tx_fee'] = round($val['income'] * 0.006,2);
            $list[$key]['own_fee'] = round($val['income'] * 0.009,2);
            $list[$key]['real_income'] = round($val['income'] * (1-0.015),2);
        }
		$this->assign('cashes', $list); // 赋值数据集
		$count = $cash->count();// 查询满足要求的总记录数
		$Page  = new \Common\Library\Tieson\PageAjax($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
        $last_page = ceil($count/10);
		$show  = $Page->show();// 分页显示输出
		$this->assign('page', $show);// 赋值分页输出
        $this->assign('last_page', $last_page);
		$this->display(); // 输出模板
	}

	/**
	 * 搜索
	 */
	public function searchAct() {

		$keywords     = '%' . I('keyword') . '%';
		$map          = array();
		$map['user_name'] = array('like', $keywords);
		// P 从 0 开始
		$p = ($_GET['p']) ? ($_GET['p']) : 0;

		$activity = M('cash');

		$list = $activity->where($map)->order('add_time')->page($p . ',10')->select();
		$this->assign('cashes', $list); // 赋值数据集
		$count = $activity->where($map)->count();// 查询满足要求的总记录数
		$Page  = new  \Common\Library\Tieson\PageAjax($count, 10);// 实例化分页类 传入总记录数和每页显示的记录数
		$show  = $Page->show();// 分页显示输出
        $last_page = ceil($count/10);
        $this->assign('last_page', $last_page);
		$this->assign("cashes", $list);
		$emptyList = "没有符合搜索条件的信息";
		$this->assign('empty', $emptyList);
		$productTabelHtml = $this->fetch("Finance/list_search.inc");
		$ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $Page->ajaxShow());
		$this->success($ajaxResult);

	}

    /**
     * 提现状态改变操作
     */
    public function changeBankStatus() {
        $cash = M('cash');
        $id = I('id');
        $where['id'] = $id;
        $data['bank_status'] = I('status');
        $res = $cash->where($where)->save($data);
        if($res){
            $this->success("操作成功");
        }else{
            $this->error('操作失败');
        }
    }

    
    public function cashInput() {
        $order_track = M('order_track');
        $where['log_order_number'] = array('neq','');
        $p = ($_GET['p']) ? ($_GET['p']) : 0;
        $user = M('user');
        $list = $order_track->where($where)->order('id desc')->page($p . ',20')->select();
        foreach ($list as $key => $val) {
            $list[$key]['user_name'] = $user->where('userid = '.$val['log_user_id'])->getField('nickname');
        }
        $this->assign('cashes', $list); // 赋值数据集
        $count = $order_track->where($where)->count();// 查询满足要求的总记录数
        $Page  = new  \Common\Library\Tieson\PageAjax($count, 20);// 实例化分页类 传入总记录数和每页显示的记录数
        $show  = $Page->show();// 分页显示输出
        $last_page = ceil($count/10);
        $this->assign('last_page', $last_page);
        $this->assign("cashes", $list);
        $emptyList = "没有相关的财务信息";
        $this->assign('empty', $emptyList);
        $productTabelHtml = $this->fetch("Finance/cash-table.inc");
        $ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $Page->ajaxShow());
        $this->success($ajaxResult);

    }

   
   
  

    
}