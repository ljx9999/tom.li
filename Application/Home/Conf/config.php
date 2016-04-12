<?php
return array(
    'URL_MODEL'		=>	2, // 如果你的环境不支持PATHINFO 请设置为3
    'DB_TYPE'		=>	'mysql',
    'DB_HOST'       =>  'localhost',
    'DB_NAME'       =>  'decorate',
    'DB_USER'       =>  'root',
    'DB_PWD'        =>  '',//presoft2015
    'DB_PORT'		=>	'3306',
    'DB_PREFIX'		=>	'dd_',
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' =>  array( // 添加输出替换
        '__UPLOAD__'    =>  __ROOT__.'/Uploads',
    ),

    'HOUSE_TYPE' => array('1室1厅1卫','2室1厅1卫','3室1厅1卫','2室2厅1卫','3室2厅1卫','4室2厅2卫'),

    'alipay_config'=>array(
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者id，以2088开头的16位纯数字//
        'partner'       => '2088121134777643',

        //安全检验码，以数字和字母组成的32位字符//
        'key'           => 'mir74pd1pqkchlpv0y9vs20rsh0ao72e',

        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

        //签名方式 不需修改
//      'sign_type'    => strtoupper('MD5'),
        'sign_type'    => strtoupper('RSA'),

        //字符编码格式 目前支持 gbk 或 utf-8
        'input_charset'=> strtolower('utf-8'),

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        'cacert'    => getcwd().'\\cacert.pem',
        
        'private_key_path' =>getcwd().'/key/rsa_private_key.pem',
        
        'ali_public_key_path' =>getcwd().'/key/alipay_public_key.pem',

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        'transport'    => 'http'
    ),
    /**************************请求参数配置**************************/
    'alipay'=>array(
        //支付类型
        'payment_type' => 1,
        //必填，不能修改
        //服务器异步通知页面路径
        'notify_url' => 'http://www.didifree.com/didifree/index.php/Home/Alipay/notifyurl',
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        //'return_url' => 'http://www.XXX.net/Model/Pay/returnurl',
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //卖家支付宝帐户
        'seller_email' => 'r17807@163.com'
        //必填
        /************************************************************/
    )
);