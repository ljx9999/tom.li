<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-22
 * Time: 上午11:54
 * To change this template use File | Settings | File Templates.
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{

    protected $_link = array(
        'Address' => array(
            'mapping_type'  => self::HAS_MANY,
            'mapping_name'  => 'address_list',
            'class_name'    => 'delivery_address',
            'foreign_key'   => 'user_id'
        ),
    );
    protected $tableName = 'admin_user';


    public function del($user_id){
        $result = 0;

        $delObj['admin_status'] = -1;  //商品状态为-1, 为删除商品(显示时为快照信息)

        $result = $this->where('id='.$user_id)->save($delObj);


        return $result;
    }
}