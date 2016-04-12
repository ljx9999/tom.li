<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;
use Home\Common\Library\WechatPay;
class WechatMessageModel extends Model{
   public function http_request($url,$data=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 我们在POST数据哦！
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function getToken(){
        $appid="wxde4b531350ffb90b";
        $appsecret="632148c8c02a022a0d3e1a51d756bf01";
        $json_token=WechatMessageModel::http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret);
        $access_token=json_decode($json_token,true);
        return $access_token;
    }

    public function wechatInfo($openid,$data,$tem_id,$red_url){
        $access_token = WechatMessageModel::getToken();
        //获得access_token
        $this->access_token=$access_token[access_token];
        //模板消息  
        $template=array(
        'touser'=>$openid,
        'template_id'=>$tem_id,
        'url'=>$red_url,
        'data'=> $data,
        );
        $json_template=json_encode($template);
        $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->access_token;
        $res=WechatMessageModel::http_request($url,urldecode($json_template));
        //if ($res[errcode]==0) echo '模板消息发送成功!';
        //print_r($res);

    }
}