<?php

namespace Admin\Action;
/**
 * Created by Sublime Text
 * User: lijixin
 * 
 */
class CommentAction extends AdminBaseAction {


	public function __construct() {
		parent::__construct();
	}

	public function index() {


		$sUserKey = $this->thisUserObj['id'];
		S($sUserKey . "_commentParam", NULL); //点击首页时, 删除缓存条件

		$resultAll = $this->getCommentList();
		$this->assign("commentList", $resultAll['commentList']);
		$this->assign("page", $resultAll['pageShow']);

		$emptyList = "暂无评论信息";
		$this->assign('empty', $emptyList);

		$this->display();
	}

	public function complaint() {

		$sUserKey = $this->thisUserObj['id'];
		S($sUserKey . "_commentParam", NULL); //点击首页时, 删除缓存条件

		$resultAll = $this->getCommentList("", 10, 2);
		$this->assign("commentList", $resultAll['commentList']);
		$this->assign("page", $resultAll['pageShow']);

		$emptyList = "暂无评论信息";
		$this->assign('empty', $emptyList);

		$this->display();
	}

	public function answer() {
		$id = $this->_get('id');
		if ($id < 1) {

		}
		$comment = $this->getCommentById($id);
		$this->assign("comment", $comment);
		$this->display();
	}

	public function answerAct() {
		$parent_id       = $this->_post('parent_id');
		$comment_content = $this->_post('comment_content');

		$this_userid = 1;

		$answer['user_id']         = $this_userid;
		$answer['parent_id']       = $parent_id;
		$answer['create_date']     = date("Y-m-d h:i:s");
		$answer['comment_content'] = html_entity_decode($comment_content);

		if (empty($answer)) {
			return "";
		}
		$commentModel = M('product_comment');
		$newid        = $commentModel->add($answer);

		if ($newid > 0) {
			$this->success("回复成功", U('answer/id/' . $parent_id));
		} else {
			$this->error("回复失败");
		}
	}

	public function deleteAct() {

		$result   = 0;
		$comments = $this->_post('ids');


		if (!empty($comments)) {

			$ids                 = join($comments, ',');
			$whereC['parent_id'] = array('in', $ids);

			$where['id'] = array('in', $ids);

			$commentModel = M('product_comment');

			$commentModel->startTrans();

			try {
				$commentModel->where($whereC)->delete();
				$result = $commentModel->where($where)->delete();
			} catch (Exception $e) {
				$commentModel->rollback();
			}
			if ($result > 0) {
				// 提交事务
				$commentModel->commit();
			} else {
				// 事务回滚
				$commentModel->rollback();
			}
		}

		if ($result > 0) {
			$this->success("删除成功!");
		} else {
			$this->error("删除失败," . $commentModel->getError());
		}
	}

	public function ajaxSearch() {

		//        $type = I("type",0);

		$sUserKey = $this->thisUserObj['id'];
		$oldParam = S($sUserKey . "_commentParam");
		$type     = isset($_REQUEST['type']) ? $_REQUEST['type'] : ((!empty($oldParam['type'])) ? $oldParam['type'] : "0");

		$emptyList = "没有符合搜索条件的投诉与建议信息";
		$this->assign('empty', $emptyList);

		$resultArr = $this->getCommentList('', 10, $type);
		$this->assign("commentList", $resultArr['commentList']);
		if ($type == 2) {
			$productTabelHtml = $this->fetch("Comment/complaints-table.inc");
		} else {
			$productTabelHtml = $this->fetch("Comment/comment-table.inc");
		}
		$ajaxResult = array("tabelBody" => $productTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
		$this->success($ajaxResult);
	}

	private function getCommentList($whereParm = "", $pre_page = "10", $type = '') {
		$map   = array();
		$where = $whereParm;


		$sUserKey = $this->thisUserObj['id'];
		$oldParam = S($sUserKey . "_commentParam");

		$keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : ((!empty($oldParam['keyword'])) ? $oldParam['keyword'] : "");

		if (!empty($keyword)) {
			$where['comment_content|comment_title'] = array("LIKE", "%$keyword%");
			$map['keyword']                         = $keyword;
		}

		$where['parent_id'] = 0;

		if (empty($type)) {
			$where['type'] = array("LT", 2);
		} else {
			$where['type'] = $type;
		}
		$map['type'] = $where['type'];

		$commentModel = new \Admin\Model\CommentModel('product_comment');

		$sort_by    = I("sort_by", "create_date");
		$sort_order = I("sort_order", "desc");
		$order      = "$sort_by $sort_order";

		$p        = empty($_GET['p']) ? 0 : $_GET['p'];
		$pre_page = $pre_page; //每一页显示多少条数据


		$count = $commentModel->where($where)->count();// 查询满足要求的总记录数
		$Page  = new \Common\Library\Tieson\PageAjax($count, $pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
		//分页跳转的时候保证查询条件
		foreach ($map as $key => $val) {
			$Page->parameter .= "$key=" . urlencode($val) . '&';
		}
		$map['p'] = $p;
		S($sUserKey . "_commentParam", $map);

		$show = $Page->show();// 分页显示输出

		$commentList              = $commentModel->relation(true)->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$resultArr['commentList'] = $commentList;
		$resultArr['pageObj']     = $Page;
		$resultArr['pageShow']    = $show;

		return $resultArr;

	}

	private function getCommentById($id) {

		$commentModel = new \Admin\Model\CommentModel('product_comment');
		$where['id']  = $id;

		$comment = $commentModel->relation(true)->where($where)->find();
		//print_r($comment);
		return $comment;
	}

}