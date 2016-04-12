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

class OrderModel extends RelationModel{

    protected $tableName = 'order';
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
        'Demand' => array(
            'mapping_type'  =>self::BELONGS_TO, 
            'class_mame'    =>'Demand',  
            'foreign_key'   =>'demand_id',
        ),
       
    );
    


    
}