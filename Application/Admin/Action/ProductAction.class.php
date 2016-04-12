<?php
namespace Admin\Action;
/**
 * Created by JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-4
 * Time: 上午14:26
 *  商品管理
 */

import('@.Tieson.Product'); // 导入Product类
import('@.Tieson.ProductIndex'); // 导入商品索引类
class ProductAction extends AdminBaseAction {


	public function __construct() {
		parent::__construct();

	}


	public function index() {
		$sUserKey = $this->thisUserObj['id'];
		S($sUserKey . "_productParam", NULL); //点击首页时, 删除缓存条件

		$resultArr = $this->getProductList();
	 	$this->assign("productList", $resultArr['productList']);
	 	$this->assign("page", $resultArr['pageShow']);
	 	$this->assign("last_page", $resultArr['count_page']);
		$this->display();
	}

	/**
	 * 商品添加
	 */
	public function add() {
		$this->display();
	}

	/**
	 * 商品编辑
	 */
	public function edit() {

		$productId = I('id');
		$product = $this->getProductById($productId);
		$this->assign("product", $product);

		$this->display();

	}

	/**
	 * 商品添加action
	 */
	public function addAct() {
		//dump(I('post.'));exit();
		$product['product_cats'] = I('product_cats');
		$product['product_brand'] = I('product_brand');
		$product['product_name'] = I('product_name');
		$product['product_number'] = I('product_number');
		$product['status']   = I('status');
		$product['product_place']   = I('product_place');
		$product['product_price'] = I('product_price');
        $product['product_merchant'] = I('product_merchant');
		$product['address']    = I('address');
		$product['recommend'] = I('recommend', 0);
		$product['product_mobile']    = I('product_mobile');
		$product['product_spec'] = I('product_spec');
		$product['product_image'] = I('product_image');
		$product['thumb_img'] = I('thumb_img');
		$product['product_desc'] = I('editorValue');
		$product['listorder'] = I('listorder');
		$product['add_time'] = time();
		$productId            = $this->productInsert($product);
		if ($productId > 0) {
			$this->success("添加辅料成功！", U('index'));
		} else {
			$this->error("添加辅料失败！");
		}

	}

	public function editAct() {
		$product_id                  = I('id');
		$product['product_cats'] = I('product_cats');
		$product['product_brand'] = I('product_brand');
		$product['product_name'] = I('product_name');
		$product['product_number'] = I('product_number');
		$product['status']   = I('status');
		$product['product_place']   = I('product_place');
		$product['product_price'] = I('product_price');
        $product['product_merchant'] = I('product_merchant');
		$product['address']    = I('address');
		$product['recommend'] = I('recommend', 0);
		$product['product_mobile']    = I('product_mobile');
		$product['product_spec'] = I('product_spec');
		$product['product_image'] = I('product_image');
		$product['thumb_img'] = I('thumb_img');
		$product['product_desc'] = I('product_desc');
		$product['listorder'] = I('listorder');
		$product['add_time'] = time();
	
		$res = $this->productUpdate($product_id, $product);

		if ($res['edit'] > 0) {
			$this->success("修改成功", U('index'));
		} else if ($res['edit'] == 0) {
			$this->success("没有修改的信息", '#');
		} else {
			$this->error("商品修改失败:" . $res['error']);
		}
	}

	public function deleteAct() {

		$result = 0;

		if (isset($_REQUEST['id'])) {
			$product_id = I('id');
			$result += $this->productRemove($product_id);

		} elseif (isset($_REQUEST['ids'])) {
			$product_ids = I('ids');
			if (!empty($product_ids)) {
				foreach ($product_ids as $key => $product_id) {
					$result += $this->productRemove($product_id);
				}
			}
		}
		if ($result > 0) {
			$this->success("删除成功!", U('index'));
		} else {
			$this->error("删除失败");
		}
	}

	public function ajaxDynamicModify() {
		$product_id = I('product_id', 0);
		$status     = I('status', 'null');
		$isHot      = I('hot', 'null');
		$recommend  = I('recommend', 'null');
		$order      = I('order', 'null');

		if ($product_id < 1) {
			$this->error('商品未找到');
		}
		$productObj = array();

		if ($isHot != 'null') {
			$productObj['is_hot'] = $isHot;
		}
		if ($recommend != 'null') {
			$productObj['recommend'] = $recommend;
		}
		if ($status != 'null') {
			$productObj['product_status'] = $status;
		}
		if ($order != 'null') {
			$productObj['product_order'] = $order;
		}

		$error = $this->productUpdate($product_id, $productObj);

		if (empty($error)) {
			$this->success('200');
		} else {
			$this->error('500');
		}
	}

	public function ajaxSearch() {

		$resultArr = $this->getProductList();
		//dump($resultArr);
		$this->assign("productList", $resultArr['productList']);
		$productTabelHtml = $this->fetch("Product/product-table.inc");
		$ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
		$this->success($ajaxResult);
	}


	private function productInsert($product = "") {
		if (empty($product)) {
			return false;
		}
		$productObj = M("product");
		$newid = $productObj->add($product);

		return $newid;
	}

	private function productUpdate($product_id,$product) {
		if (empty($product)) {
			throw_exception("商品未改变");
			return false;
		}
		$productModel = M("product");

		$res['edit'] = $productModel->where("id=".$product_id)->save($product);

		$res['error'] = $productModel->getDbError();

		return $res;
	}


	/**
	 * 物理删除商品信息
	 * @param $product_id
	 * @return int
	 */
	private function physicsProductRemove($product_id) {

		$result = 0;

		$productModel = D('product');
		$this->data   = $productModel->relation(true)->where('id=' . $product_id)->find();

		$productModel->startTrans();
		if (!empty($this->data)) {
			$result = $productModel->relation(true)->where('id=' . $product_id)->delete($this->data);
		}
		if ($result > 0) {
			// 提交事务
			$productModel->commit();
		} else {
			// 事务回滚
			$productModel->rollback();
		}

		return $result;

	}

	/**
	 * 逻辑删除商品信息
	 * @param $product_id
	 * @return int
	 */
	private function productRemove($product_id) {

		$productModel = M('product');

		$delObj['status'] = -1;  //商品状态为-1, 为删除商品(显示时为快照信息)

		$result = $productModel->where('id=' . $product_id)->save($delObj);

		return $result;

	}


	private function getProductList($whereParm = "", $pre_page = "10", $countNum = 0) {
		$resultArr = array();
		$where     = $whereParm;

		$sUserKey = $this->thisUserObj['id'];
		$oldParam = S($sUserKey . "_productParam");
		$map      = array();

		$keyword         = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : ((!empty($oldParam['keyword'])) ? $oldParam['keyword'] : "");
		$keyword         = isset($_REQUEST['status']) ? $_REQUEST['status'] : ((!empty($oldParam['status'])) ? $oldParam['status'] : "");

		if (!empty($keyword)) {
			$keyword  = trim($keyword);
			$keyWords = explode(" ", $keyword);
			$likeArr  = array();

			foreach ($keyWords as $key => $value) {
				$l         = trim($value);
				$likeStr   = array("LIKE", "%$l%");
				$likeArr[] = $likeStr;
			}
			array_push($likeArr, 'and');
			$where['product_name|product_number'] = $likeArr;
			$map['keyword']                       = $keyword;
		}

		if ($product_status != "") {
			$where['status'] = $product_status;
			$map['status']   = $product_status;
		} else {
			$where['status'] = array("egt", 0);
		}


		$productModel = M('Product');
		if (!empty($sort_by) && !empty($sort_order)) {
			$sort_by    = I("sort_by", "product_order");
			$sort_order = I("sort_order", "asc");
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


		$p        = empty($_GET['p']) ? 0 : $_GET['p'];
		$pre_page = $pre_page; //每一页显示多少条数据


		if ($countNum == 0) {
			$count      = $productModel->where($where)->count();// 查询满足要求的总记录数
			$count_page = ceil($count / 10);
		} else {
			$count = $countNum;
		}

		$Page = new \Common\Library\Tieson\PageAjax($count, $pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
		//分页跳转的时候保证查询条件
		foreach ($map as $key => $val) {
			$Page->parameter .= "$key=" . urlencode($val) . '&';
		}
		$map['p'] = $p;

		S($sUserKey . "_productParam", $map);

		$show = $Page->show();// 分页显示输出
		$cats = M('category');
		$productList = $productModel->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($productList as $key => $val) {
			$re_cat = $cats->where('id='.$val['product_cats'])->getField('cat_name');
			$productList[$key]['cat_name'] = $re_cat;
		}
		$resultArr['keys']        = $sort_by;
		$resultArr['count_page']  = $count_page;
		$resultArr['productList'] = $productList;
		$resultArr['pageObj']     = $Page;
		$resultArr['pageShow']    = $show;
		return $resultArr;
	}


	/**
	 * 通过商品Id , 取商品信息
	 * @param string $productId 商品Id
	 * @return mixed  商品详情信息
	 */
	private function getProductById($productId = "") {

		$where['id'] = $productId;

		$productModel                = M('Product');
		$product                     = $productModel->where($where)->find();
		$cats = M('category');
		$re_cat = $cats->where('id='.$product['product_cats'])->getField('cat_name');
		$product_describe            = stripslashes($product['product_desc']);
		$product['product_desc'] = $product_describe;
		$product['cat_name'] = $re_cat;

		return $product;
	}


	
	
}