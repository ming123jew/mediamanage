<?php
namespace mediamanage;
use think\Request;
use mediamanage\controller\Index;
defined('MEDIAMANAGE_VIEW_PATH') or define('MEDIAMANAGE_VIEW_PATH', __DIR__ . DS.'view'. DS);
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
		//print_r($this->request);
		$controller = new Index($this->request);

		if(strtolower($this->controller) == 'index' && method_exists($controller,$name)){
			
			return  call_user_func([$controller, $name]);
		}
	
		return false;
	}
	

	
}