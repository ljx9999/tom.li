<?php
namespace Home\Model;
use Think\Model\RelationModel;
use Think\Model;

        class SmsModel extends Model{
         /**
         * 短信接口
         * @access protected
         * @param mixed $data 要返回的数据
         * @param String $type AJAX返回数据格式
         * @return void
         */
            Public function sendSmsCode($mobile,$code) {
                $post_data = array();
                $post_data['userid'] = 281;
                $post_data['account'] = '滴滴装修';
                $post_data['password'] = '123456';
                $post_data['content'] = '【滴滴装修】您的注册验证码为：'.$code.'，请妥善保管。'; //短信内容需要用urlencode编码下
                $post_data['mobile'] = $mobile;
                $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
                $url='http://115.28.50.135:8888/sms.aspx?action=send';
                $o='';
                $data['mobile'] = $mobile;
                $data['code'] = $code;
                $data['create_time'] = time();
                $sms = M('sms_verification');
                $addCode = $sms->add($data);

                foreach ($post_data as $k=>$v)
                {
                    $o.="$k=".urlencode($v).'&';
                }
                $post_data=substr($o,0,-1);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
                $result = curl_exec($ch);
                if($result){
                    return json_encode(array("info"=>"","status"=>1));
                }else{
                    return json_encode(array("info"=>"","status"=>0));
                }

            }
            /*发布需求发手机短信*/
             Public function sendSmsDemand($mobile) {
                $post_data = array();
                $post_data['userid'] = 281;
                $post_data['account'] = '滴滴装修';
                $post_data['password'] = '123456';
                $post_data['content'] = '【滴滴装修】您的订单有更新，请登录微信公众号"滴滴装修"，进行回复。'; //短信内容需要用urlencode编码下
                $post_data['mobile'] = $mobile;
                $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
                $url='http://115.28.50.135:8888/sms.aspx?action=send';
                $o='';
                foreach ($post_data as $k=>$v)
                {
                    $o.="$k=".urlencode($v).'&';
                }
                $post_data=substr($o,0,-1);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
                curl_exec($ch);

                // if($result){
                //     return json_encode(array("info"=>"","status"=>1));
                // }else{
                //     return json_encode(array("info"=>"","status"=>0));
                // }

            }

            /*消息推送接口*/
            public function send_message($message,$tag){
              if(empty($message)){
                echo 'isnull';
                return false;
              }
              $jpush = new \Common\Library\Tieson\Jpush();
                 //组装需要的参数
              if(empty($tag)){
                    $receive = 'all';     //全部
              }else{
                    $receive = array('tag'=>array($tag));      //标签
              }
               
                //$receive = array('tag'=>array('2401','2588','9527'));      //标签
                //$receive = array('alias'=>array('93d78b73611d886a74*****88497f501'));    //别名
                $content =  $message;
                $m_type = 'http';
                $m_txt = 'http://www.didifree.com/';
                $m_time = '600';        //离线保留时间
             
                //调用推送,并处理
                $result = $jpush->push($receive,$content,$m_type,$m_txt,$m_time);
                // if($result){
                //     $res_arr = json_decode($result, true);
                //     if(isset($res_arr['error'])){                       //如果返回了error则证明失败
                //         echo $res_arr['error']['message'];              //错误信息
                //         echo $res_arr['error']['code'];                 //错误码
                //         return false;       
                //     }
                // }
            }

}