<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;
class ProductModel extends Model{
 /**
 * 辅料查询
 * @access public
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @return void
 */

    public function getProductList($page){
        $cat_arr = $this->getAllPro(0,$page);
        return  $cat_arr;
    }

    Public function getAllPro($pid=0,$page) {
        $cat_arr = array();
        $where['pid'] = $pid;
        $where['status'] = 1;
        $category = M('category');
        $order = "listorder DESC";
        $count = $category->where($where)->count();// 查询满足要求的总记录数
        $start = $page * 2; 
        $sql = "select * from `dd_category` where `pid` = 0 AND `status` = 1 ORDER BY listorder DESC limit $start,2";
        $cat_arr = $category->query($sql);
        foreach($cat_arr as $key=>$val){
            $wheres['pid'] = $val['id'];
            $wheres['status'] = 1;
            $childs_cat = $category->where($wheres)->select();
            $cat_arr[$key]['childs_cat'] = $childs_cat;
        }
        return $cat_arr;

    }

    public function getProductsList($id,$page){
        $pro_arr = $this->getCatProList($id,$page);
        return $pro_arr;
    }

    public function getCatProList($id,$page){
        $products = M('product');
        $pro_arr = array();
        $where['product_cats'] = $id;
        $where['status'] = 1;
        $order = "listorder DESC";
        $count = $products->where($where)->count();// 查询满足要求的总记录数
        $start = $page * 2; 
        $sql = "select * from `dd_product` where `product_cats` = $id AND `status` = 1 ORDER BY listorder DESC limit $start,2";
        $pro_arr = $products->query($sql);
        $pro_collect = M('pro_collect');
        foreach ($pro_arr as $key => $val) {
            $datas['userid'] = $userid;
            $datas['pro_id'] = $val['id'];
            $res = $pro_collect->where($datas)->find();
            if($res){
                $pro_arr[$key]['collects'] = 1;
            }else{
                $pro_arr[$key]['collects'] = 0;
            }
        }
        return $pro_arr;
    }



}