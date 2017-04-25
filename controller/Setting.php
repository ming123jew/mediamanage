<?php
namespace mediamanage\controller;

use mediamanage\controller\Base;
class Setting extends Base
{
	function __construct($request){
		parent::__construct($request);
	}
	/**
	 * 列表
	 */
	function Index(){
		
		return [MEDIAMANAGE_VIEW_PATH.'setting/index.php',array_merge($this->data)];
	}
	
	

}
