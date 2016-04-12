<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mackcyl
 * Date: 13-9-5
 * Time: 下午6:33
 * To change this template use File | Settings | File Templates.
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class AnswerModel extends RelationModel{

    protected $tableName = 'advice';
    protected $_link = array(
        "user" => array(
            "mapping_type"  => self::HAS_ONE,                  //关联类型
            "mapping_name"  => "realname",               //关联变量名
            "mapping_fields"=> "realname",              //查询关联字段
            "class_name"    => "user",                   //关联类名
            "foreign_key"   => "user_id",                //外健
            "as_fields"     => "realname:realname"   //别名

        ),
    );
}