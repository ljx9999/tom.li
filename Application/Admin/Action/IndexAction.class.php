<?php
/**
 * Created by Sublime Text
 * User: lijixin
 * 
 */
namespace Admin\Action;

class IndexAction extends AdminBaseAction{

    private $menu_db ;


    public  function __construct(){
        parent::__construct();
    }




    public function  index(){


       
		$this->display();
	}


  

}
