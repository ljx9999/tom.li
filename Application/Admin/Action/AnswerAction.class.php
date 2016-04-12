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

class AnswerAction extends AdminBaseAction {

	/**
	 * 首页
	 */
	public function index() {
		$sUserKey = $this->thisUserObj['id'];
		S($sUserKey . "_billboardParam", NULL); //点击首页时, 删除缓存条件

		$resultArr = $this->getList();
		$emptyList = "没有答疑信息。";
		$this->assign('empty', $emptyList);

		$this->assign("billboardList", $resultArr['billboardList']);
		$this->assign("page", $resultArr['pageShow']);
		$this->assign('last_page',$resultArr['last_page']);
		$this->display();
	}


	public function deleteAct() {
		$result = 0;
        $id = I('id');
        $ids = I('ids');

		if(!empty($id)){
			$result += $this->delBillboard($id);
		}else{
			foreach ($ids as $key => $k) {
				$result += $this->delBillboard($k);
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
     * @param $billboardId
     */
    public function delBillboard($billboardId){
         $billboardModel = M('advice');
        if(empty($billboardId)){ //数据对象创建错误
            return 0;
        }
        $billboard['id'] = $billboardId;
        $billboard['status'] = -1;
        if( $billboardModel->save($billboard)){
            return 1;
        }else{
            return 0;
        }

        
        
    }

	 /**
     * 取咨询类别列表
     */
    public function getList($whereParam=""){
        $billboardList = array();
        $where = $whereParam;

        $sUserObj = session(C('ADMIN_USER_KEY'));
        $sUserKey = $sUserObj['id'];
        $oldParam = S($sUserKey."_billboardParam");
        $map = array();

        $where['status'] = array("EGT",0);
        $keyword = isset($_REQUEST['keyword'])?$_REQUEST['keyword']:((!empty($oldParam['keyword']))?$oldParam['keyword']:"");   
        $add_time = isset($_REQUEST['add_time'])?$_REQUEST['add_time']:((!empty($oldParam['add_time']))?$oldParam['add_time']:"");
       
        if(!empty($keyword)){
            $where['message|answer'] = array("LIKE","%$keyword%");
            $map['keyword'] = $keyword;
        }
       
        if(!empty($add_time)){
            $where['add_time'] = array('glt',$add_time);
            $map['add_time'] = $add_time;
        }
        if (!empty($sort_by) && !empty($sort_order)) {
            $sort_by    = I("sort_by", "id");
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

        $p = empty($_GET['p'])?0:$_GET['p'];
        $answerModel = M('advice');
        $pre_page = 10; //每一页显示多少条数据
        import('@.CN.Tieson.PageAjax'); // 导入分页类
        $users = M('user');
        $count      =  $answerModel->where($where)->count();// 查询满足要求的总记录数
        
        $Page       = new \Common\Library\Tieson\PageAjax($count,$pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
        $last_page = ceil($count/10); 
        //分页跳转的时候保证查询条件
        foreach($map as $key=>$val) {
            $Page->parameter   .=   "$key=".urlencode($val).'&';
        }
        $map['p'] = $p;
        S($sUserKey."_billboardParam",$map);
        $show       = $Page->show();// 分页显示输出
       
        $list =  $answerModel->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list as $key => $val) {
        	$relname = $users->where('userid='.$val['user_id'])->getField('realname');
        	$list[$key]['user_name']=$relname;
        	if(mb_strlen($val['message'],'utf-8') > 30){
        		$list[$key]['short_message'] = mb_substr($val['message'],0,30, 'utf-8').'……'; 
        	}else{
        		$list[$key]['short_message'] = $val['message']; 
        	}
        	if(mb_strlen($val['answer'],'utf-8') > 30){
        		$list[$key]['short_answer'] = mb_substr($val['answer'],0,30, 'utf-8').'……'; 
        	}else{
        		$list[$key]['short_answer'] = $val['answer'];
        	}
        	
        }
        $billboardList['billboardList'] = $list;
        $billboardList['pageObj'] = $Page;
        $billboardList['pageShow'] = $show;
        $billboardList['last_page'] = $last_page;

        return $billboardList;
    }

    //搜索操作
	public function ajaxSearch() {
		$resultArr = $this->getList();
		$this->assign("billboardList", $resultArr['billboardList']);
		$emptyList = "没有符合搜索条件的信息。";
		$this->assign('empty', $emptyList);
		$productTabelHtml = $this->fetch("Answer/billboard-table.inc");
		$ajaxResult       = array("tabelBody" => $productTabelHtml, 'pageBody' => $resultArr['pageObj']->ajaxShow());
		$this->success($ajaxResult);
	}
    //获取咨询问题信息
    public function getMessage(){
        $id = I('id');
        if(empty($id)){
            $this->error('网络错误，请稍后！');
        }else{
            $answerModel = M('advice');
            $users = M('user');
            $list =  $answerModel->where('id='.$id)->find();
            $user_name = $users->where('userid='.$list['user_id'])->getField('realname'); 
            $list['user_name'] = $user_name;
            $this->success($list);
        }

    }
    //回复内容写入操作
    public function ajaxSubAnswer(){
        $id = I('id');
        $answer = I('answer');
        $answerModel = M('advice');
        $where['id'] = $id;
        $data['answer'] = $answer;
        $data['answer_time'] = time();
        $data['status'] = 1;
        $res = $answerModel->where($where)->save($data);
        if($res){
            $this->success('回复成功！');
        }

    }

}