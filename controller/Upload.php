<?php
namespace mediamanage\controller;
use mediamanage\controller\Base;
class Upload extends Base
{
	function __construct($request){
		parent::__construct($request);
	}
	
	/**
	 * @desc   上传excel
	 * @return multitype:number string
	 */
	function Index(){
	
		echo "ok";
		return ['code'=>11,'msg'=>'test'];
	}
	
	
}