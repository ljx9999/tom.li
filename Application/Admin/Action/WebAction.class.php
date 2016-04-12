<?php
/**
 * Created by PhpStorm.
 * User: lijixin
 * Date: 15-6-18
 * 
 *
 * 答疑管理
 */
namespace Admin\Action;
use Org\Util\Date;

class WebAction extends AdminBaseAction {

	/**
	 * 首页
	 */
	public function index() {
		$yijian = M("yijian"); // 实例化User对象
        $list = $yijian->select();
        $this->assign('list',$list);
		$this->display();
		}
}