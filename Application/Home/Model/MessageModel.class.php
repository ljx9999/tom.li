<?php
namespace Home\Model;
use Think\Model;

class MessageModel extends Model {
	public function addMessage($data){
		$Message=M('advice');

		$data = json_decode($data,true);
		 
		$AddMessage=$Message->add($data);
		if($AddMessage){
			return json_encode(array("info"=>'',"status"=>1));
		}else{
		     return json_encode(array("info"=>'',"status"=>0));
		}
	}
}