<?php
/**
 * Created by Sublime Text
 * User: lijixin
 * 
 */
namespace Admin\Action;
class CategoryAction extends AdminBaseAction{

    private $parentId;
    private $db;


    public function __construct(){
        parent::__construct();

        $this->parentId = I('parentId');
        $this->db = D("Category");
    }

    public function index(){

        $category = $this->getCategoryList(0);
        //dump($category);
        $ret = $this->getTree($category);
        
        $this->assign('ret', $ret);
        $this->assign('page', $category['pageShow']);
        $this->display();
    }

    public function add(){
        $parentObj = $this->getCategoryById($this->parentId);
        if(!isset($parentObj['id']) || $parentObj['id'] == 0 ){
            $parentObj['id'] = 0;
            $parentObj['cat_name'] = '顶级分类';
        }
        $this->assign("parentObj",$parentObj);
        $this->display();
    }

    public function addAct(){
        $category = array();
        $category['cat_name'] = I('cat_name');
        $category['pid'] = I('pid');
        $category['listorder'] = I('listorder');
        $category['status'] = I('status');
        $category['img_src'] = I('img_src');
        $category['thumb_img'] = I('thumb_img');
        $category['add_time'] = time();
        if($this->insert($category)){
            $this->success('新增成功', U('index'));
        }else{
            $this->error('新增失败');
        }
    }

    public function edit(){

        $id = I('id');

        $categoryObj = $this->getCategoryById($id);

        if(!isset($categoryObj['id']) || $categoryObj['id'] == 0 ){
            $this->error("未找到要修改的分类");
        }

        $parentObj = $this->getCategoryById($categoryObj['pid']);
        if(!isset($parentObj['id']) || $parentObj['id'] == 0 ){
            $categoryObj['parentname'] = '顶级分类';
        }else{
            $categoryObj['parentname'] = $parentObj['cat_name'];
        }

        $this->assign("category",$categoryObj);
        $this->display();
    }



    public function editAct(){
        $category = array();

        $id = I('id');

        $category['cat_name'] = I('cat_name');
        $category['listorder'] = I('listorder');
        $category['status'] = I('status');
        $category['img_src'] = I('img_src');
        $category['thumb_img'] = I('thumb_img');
        $category['add_time'] = time();

        if(empty($id)){
            $this->error("修改失败");
        }


        $edit = $this->update($category,$id);   
        if ($edit > 0) {
            $this->success("修改成功", U('index'));
        } else if ($edit === 0) {
            $this->success("没有修改的信息",'#');
        }else{
            $this->error('修改失败','#');
        }
        
    }


    public function remove(){
        /*
         * 通过获得的ID删除页面
         */
        $categoryObj = M('category');


        $ids = I('ids');
//        $isparent = $this->_post('isparent');

        $res = 1;
        foreach($ids as $key=>$id){
            $res = $this->delete($id);
            $cats = $this->getCategoryList($id);
            if(!empty($cats)){
                $res= $this->deleteChild($cats);
            }
        }

        if ($res !== false) {
            $this->success('删除成功');
        }
        else {
            $this->error('删除失败');
        }
    }

    public function ajaxSearchCategory(){

        $keyword = "";

        $keyword = I("keyword");

        $where = array();

        if(!empty($keyword)){
            $where['cat_name'] = array("LIKE",'%'.$keyword.'%');
            $categoryModel = D('Category');
            $category = $categoryModel->where($where)->select();
        }else{
            $category = $this->getCategoryList(0);
        }

        $ret = $this->getTree($category);
        $this->assign('ret', $ret);

        $categoryTabelHtml = $this->fetch("Category/category-table.inc");

        $this->success($categoryTabelHtml);

    }


    public function ajaxGetCategory(){
        $parentId = I('parentId');

        $where = array();

        if (isset($parentId))
            $where['pid'] = $parentId;

        if ( $parentId == '100000'){
            return ;
        }

        $categories = $this->db->where($where)->select();
        $this->success($categories);
    }




    public function ajaxDynamicModify(){
        $cat_id = I('cat_id',0);
        $order = I('order','null');

        if($cat_id < 1){
            $this->error('分类未找到');
        }
        $catObj = array();

        if($order != 'null'){
            $catObj['listorder'] = $order;
        }

        $catModel = M("category");

        $catModel->where("id=$cat_id")->save($catObj);

        $error = $catModel->getDbError();

        if(empty($error)){
            $this->success('200');
        }else{
            $this->error('500');
        }
    }
    
  


   
    
  
    private function getTree($category, $tab = 0)
    {
        $strHtml = "";
        $str = "";
        $LANG = l('status');
        while ($cat = array_pop($category)) {

            $isChild = 0;

            if ($cat['childs'] != null) {
                $isChild = 1;
            }
            $status = $cat['status'];

            $moduleHtml = "";
            $str .= '<tr class="category_tab_id_'.$tab.'_'.$cat['pid'] .'" id="'.$cat['id'].'" title="单击显示或隐藏子分类">'.
                        '<td>'.
                        '   <input type="checkbox" name="cat_checkbox" id="cat_'.$cat['id'].'">'.
                        '</td>'.
                        '<td > '.
                            str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $tab) . $cat['cat_name'] . '&nbsp;'.

                        '</td>'.
                        '<td>'.
                            date("Y-m-d",$cat['add_time']).
                        '</td>'.
                        '<td>'.
                            (($cat['status']>0)?'启用':'关闭'). 
                        '</td>'.
                        '<td>'.
                            '<span style="cursor: pointer;" class="edit_input" id="span_order_'.$cat['id'].'" name="order_'.$cat['id'].'">'.$cat['listorder'].'</span>'.
                            '<span>'.
                                '<input type="input" class="inputOrder" id="input_order_'.$cat['id'].'"  value="'.$cat['listorder'].'" style="display: none;">'.
                            '</span>'.
                        '</td>'.
                        '<td>'.
                        '   <div class="formGroup">'.
                        '       <a href="' . U('Category/add?parentId='.$cat['id']) . '" class="button button-rounded button-tiny button-flat-caution"> 添加子类 </a>'.
                (($cat['attr_switch']>0)?'       <a href="' . U('Category/attr?id='.$cat['id']) . '" class="button button-rounded button-tiny button-flat-caution"> 属性 </a>':'').
                        '       <a href="' . U('Category/edit?id='.$cat['id']) . '" class="button button-rounded button-tiny button-flat-caution"> 编辑 </a>'.
                        '       <a href="javascript:void(0)" alt="删除" id="page_delete_'.$cat['id'].'_'.$isChild.'"   class="del button button-rounded button-tiny button-flat-caution"> 删除 </a>&nbsp;'.
                        '   </div>'.
                        '</td>'.
                '</tr>';

            if ($isChild) {
                $str .= $this->getTree($cat['childs'], $tab + 1);
            }
        }
        if(!empty($str)){
//            $strHtml .= "<tbody class='datalistclass'>" ;
            $strHtml .= $str ;
//            $strHtml .= "</tbody>";
            $str = "";
        }

        return $strHtml;
    }

    private function insert($category=""){
        if(empty($category)){
            return false;
        }
        $catObj = M("category");
        $newid = $catObj->add($category);
        return $newid;
    }

    private function update($category , $id){

        if(empty($category)){
            return false;
        }
        $catObj = M("category");

        $id = $catObj->where("id=$id")->save($category);


        return $id;
    }

    private function deleteChild($childArr = ""){
        while ($cat = array_pop($childArr)) {

            $isChild = 0;

            if ($cat['childs'] != null) {
                $isChild = 1;
            }
            $id = $cat['id'];

            $ras = $this->delete($id);

            if ($isChild) {
                $this->deleteChild($cat['childs']);
            }

        }

        return $ras;
    }

    private function delete($id){
        if($id < 1){
            return 0;
        }
        $categoryObj = M('category');
        $res = $categoryObj->where("`id` IN ($id)") -> delete();

        return $res;
    }

    private function getCategoryList($parentid=0 ,$whereParm="",$pre_page = "10"){
        $category = array();
        $where = $whereParm;
        $where['pid'] = $parentid;
        $categoryModel = D('Category');

        $order = "listorder DESC";
        $category = $categoryModel->where($where)->order($order)->select();
		$count = $categoryModel->count();// 查询满足要求的总记录数
		$Page  = new \Common\Library\Tieson\PageAjax($count, $pre_page);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        foreach($category as $key=>$categoryObj){
            if(!empty($categoryObj['id'])){
                $category[$key]['childs'] = $this->getCategoryList($categoryObj['id']);
            }
            continue;
        }
        return $category;
    }

    private function getCategoryById($id=""){
        if(empty($id)){
            return "";
        }

        $catObj = $this->db->where("id=$id")->find();

        return $catObj;
    }


}