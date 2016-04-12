<?php
namespace Admin\Model;
use Think\Model\RelationModel;

class CategoryModel extends RelationModel {

	/*
	 * 取分类列表(递归)
	 */
	public function loadCategoryTreeList($parentid = 0, $whereParm = "") {
		$category          = array();
		$where             = $whereParm;
		$where['pid'] = $parentid;

		$order    = "listorder DESC";
		$category = $this->where($where)->order($order)->field('id,pid,cat_name')->select();
		foreach ($category as $key => $categoryObj) {
			if (!empty($categoryObj['id'])) {
				$category[$key]['childs'] = $this->loadCategoryTreeList($categoryObj['id']);
			}
			continue;
		}
		return $category;
	}

	/**
	 *
	 */
	public function createCategorySelectJsonData($category, $level = 0) {
		$categoryList = array();
		while ($cat = array_pop($category)) {

			$isChild = 0;

			if ($cat['childs'] != null) {
				$isChild = 1;
			}
			$categoryList[] = array('value' => $cat['id'], 'label' => str_repeat('--', $level) . $cat['cat_name']);

			if ($isChild) {
				$childCategoryList = $this->createCategorySelectJsonData($cat['childs'], $level + 1);
				if (!empty($childCategoryList) && is_array($childCategoryList)) {
					foreach ($childCategoryList as $child) {
						$categoryList[] = $child;
					}
				}
			}
		}
		return $categoryList;
	}

}
