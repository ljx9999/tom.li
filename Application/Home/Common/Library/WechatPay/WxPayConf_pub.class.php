<?php
/**
* 	配置账号信息
*/

namespace Home\Common\Library\WechatPay;

class WxPayConf_pub
{
	//=======【基本信息设置】=====================================
	//微信公众号身份的唯一标识。审核通过后，在微信发送的邮件中查看
	const APPID = 'wxde4b531350ffb90b';
	//受理商ID，身份标识
	const MCHID = '1240979502';
	//商户支付密钥Key。审核通过后，在微信发送的邮件中查看
	const APPSECRET = '632148c8c02a022a0d3e1a51d756bf01';
	//JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
	const KEY = '98763be843805265451854d0035be6bc';
	
	//=======【JSAPI路径设置】===================================
	//获取access_token过程中的跳转uri，通过跳转将code传入jsapi支付页面
	const JS_API_CALL_URL = 'http://www.didifree.com/didifree/Home/WechatPay/weixinJsPay';
	
	//=======【证书路径设置】=====================================
	//证书路径,注意应该填写绝对路径
	const SSLCERT_PATH = '/didifree/key/apiclient_cert.pem';
	const SSLKEY_PATH = '/didifree/key/apiclient_key.pem';
	
	//=======【异步通知url设置】===================================
	//异步通知url，商户根据实际开发过程设定
	const NOTIFY_URL = 'http://www.didifree.com/didifree/Home/WechatPay/wxpayNotify';

	//=======【curl超时设置】===================================
	//本例程通过curl使用HTTP POST方法，此处可修改其超时时间，默认为30秒
	const CURL_TIMEOUT = 30;

	const CURL_PROXY_HOST = "0.0.0.0";//"10.152.18.220";
	const CURL_PROXY_PORT = 0;//8080;
}
	
?>