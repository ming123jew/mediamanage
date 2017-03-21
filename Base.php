<?php
namespace mediamanage\base;
use think\Request;
use MediaManage\controller\Index;

class Base {
	
	public function __construct()
	{
		$this->request      = Request::instance();
		$this->param        = $this->request->param();
		$this->module       = $this->request->module();
		$this->controller   = $this->request->controller();
		$this->action       = $this->request->action();
	
	}
	
	/**
	 * 加载控制器方法
	 * @access public
	 * @param  string  $name 方法名
	 * @return mixed
	 */
	public function autoload($name){
	
		$controller = new Index($this->request);
	
		if(strtolower($this->controller) == 'MediaManage' && method_exists($controller,$name)){
			return  call_user_func([$controller, $name]);
		}
	
		return false;
	}
	

	
}