<?php
/**
 * Created by PhpStorm.
 * User: lijixin
 * Date: 15-6-18
 * 
 *
 * ���ɹ���
 */
namespace Admin\Action;
use Org\Util\Date;

class WebAction extends AdminBaseAction {

	/**
	 * ��ҳ
	 */
	public function index() {
		$yijian = M("yijian"); // ʵ����User����
        $list = $yijian->select();
        $this->assign('list',$list);
		$this->display();
		}
}