<?php
namespace Home\Common\Library\WechatPay;

use Think\Exception;

class  SDKRuntimeException extends Exception {
	public function errorMessage()
	{
		return $this->getMessage();
	}

}

?>