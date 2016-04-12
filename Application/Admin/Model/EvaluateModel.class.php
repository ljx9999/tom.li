<?php
/**
 * Created by PhpStorm.
 * User: mackcyl
 * Date: 13-11-15
 * Time: 上午11:31
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class EvaluateModel extends RelationModel{
    protected $tableName = 'evaluate';
     // 是否自动检测数据表字段信息  
    protected $autoCheckFields =false;
    
    protected $_link = array(
        'Foreman'  =>  array(
            'mapping_type'  =>self::BELONGS_TO,           //关联类型
            'class_mame'    =>'Foreman',              //需要关联的模型类名
            'mapping_name'  =>'workerName',          //关联的映射名称，用于获取数据用
            'foreign_key'   =>'worker_id',           //关联的外键名称
            'mapping_fields'=>'realname',         //关联要查询的字段
            'as_fields'     =>'realname:workerName'
        ),
       
    );
}