<?php
namespace Admin\Action;
/*
 * Created by lijixin
 * 用户管理模块 
 */
class JpushAction extends AdminBaseAction {
     /**
     * 首页
     */
    public function index(){
        $this->display();
    }

    public function send_message(){
      $content = I('message');
      if(empty($content)){
        echo 'isnull';
        return false;
      }
      $jpush = new \Common\Library\Tieson\Jpush();
         //组装需要的参数
        $receive = 'all';     //全部
        //$receive = array('tag'=>array('2401','2588','9527'));      //标签
        //$receive = array('alias'=>array('93d78b73611d886a74*****88497f501'));    //别名
        $content =  $content;
        $m_type = 'http';
        $m_txt = 'http://www.didifree.com/';
        $m_time = '600';        //离线保留时间
     
        //调用推送,并处理
        $result = $jpush->push($receive,$content,$m_type,$m_txt,$m_time);
        if($result){
            $res_arr = json_decode($result, true);
            if(isset($res_arr['error'])){                       //如果返回了error则证明失败
                echo $res_arr['error']['message'];          //错误信息
                echo $res_arr['error']['code'];             //错误码
                return false;       
            }else{
                //处理成功的推送......
                echo 'succ';
                return true;
            }
        }else{      //接口调用失败或无响应
            echo 'fail';
            return false;
        }
    }



    

}