<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi.cn@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// config.php 2013-02-25

//定义回调URL通用的URL

return array(
	//项目运行模式
	'APP_STATUS'            => 'debug',
	
    'MODULE_ALLOW_LIST'     =>  array('Home','Admin'), // 配置你原来的分组列表
    'DEFAULT_MODULE'        =>  'Home', // 配置你原来的默认分组


    'LANG_SWITCH_ON'        =>  true,   // 开启语言包功能
    'DEFAULT_LANG'          => 'zh-cn', // 默认语言
    'LANG_LIST'             => 'zh-cn', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'          => 'l',     // 默认语言切换变量

    'URL_CASE_INSENSITIVE' =>true,


	//cookie设置
    /* Cookie设置 */
    'COOKIE_EXPIRE'         => 0,    // Coodie有效期1个月 60*60*24*30
    'COOKIE_PREFIX'         => 'dd_',      // Cookie前缀 避免冲突


	//数据库配置 数据库连接配置参数DB_HOST如果配置的是localhost或者域名，请修改为ip地址，否则会导致数据库连接缓慢（这是PHP5.3的机制问题 非TP问题）
    'DB_TYPE'               => 'mysql',     // 数据库类型192.168.2.31
	'DB_HOST'               => 'localhost', // 服务器地址127.0.0.1
    'DB_NAME'               => 'decorate',      // 数据库名 sdp
    'DB_USER'               => 'root',      // 用户名
    'DB_PWD'                => '',      // 密码presoft2015
    'DB_PORT'               => '3306',      // 端口
    'DB_PREFIX'             => 'dd_',     // 数据库表前缀
    
    //URL设置
    'URL_MODEL'             => 2,
    //页面输出压缩
    'OUTPUT_ENCODE'         =>  false, // 页面压缩输出
    'SHOW_PAGE_TRACE'       =>  false,
    'LOAD_EXT_CONFIG'         => 'user', //加载扩展配置文件
);

