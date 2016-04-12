<?php
namespace Home\Controller;
use Home\Model\ProductModel;
use Think\Controller;
class ProductController extends FrontBaseController {
    function __construct() {
        parent::__construct();
        
    }
    /*辅料分类首页*/
    public function index(){

        $this->display();
    }

    /*获取辅料分类列表*/
    public function ajaxGetProList($page=0){
        $ProductModel = D('Product');
        $pro_list = $ProductModel->getProductList($page);
        $this->ajaxReturn($pro_list );
    }

    /*辅料列表*/
    public function productList(){
        $id = I('id');
        $cat = M('category');
        $cat_name = $cat ->where('id='.$id)->getField('cat_name');
        $this->assign('id',$id);
        $this->assign('cat_name',$cat_name);
        $this->display();
    }

    /*获取辅料列表*/
    public function ajaxProductList(){
        $id = I('id');
        $page = I('page');
        $ProductModel = D('Product');
        $pro_list =  $ProductModel->getProductsList($id,$page);
        $this->ajaxReturn($pro_list);
    }

    public function ajaxCollect(){
        $id = I('id');   
        $user_id = $_SESSION['uid'];
        $pro_collect = M('pro_collect');
        $data['userid'] = $user_id;
        $data['pro_id'] = $id;
        $data['add_time'] = time();
        $data['status'] = 1;
        $res = $pro_collect->add($data);
        if($res){
            $this->success('收藏成功');
        }else{
            $this->error('收藏失败！');
        }
    }  
}