<?php
namespace mediamanage;
use think\Request;
use think\Config;
defined('WEB_PUBLIC') or define('WEB_PUBLIC',ROOT_PATH . 'public' . DS);
defined('MEDIAMANAGE_VIEW_PATH') or define('MEDIAMANAGE_VIEW_PATH', __DIR__ . DS.'view'. DS);
defined('MEDIAMANAGE_DIR') or define('MEDIAMANAGE_DIR',__DIR__. DS);
defined('MEDIAMANAGE_STYLE_PATH') or define('MEDIAMANAGE_STYLE_PATH', '/static/mediamanage/webuploader-0.1.5/');
defined('MEDIAMANAGE_UPLOAD_PATH') or define('MEDIAMANAGE_UPLOAD_PATH',WEB_PUBLIC . 'uploads'. DS . 'mediamanage'. DS );

class Base {
	
	public function __construct()
	{
        $this->request      = Request::instance();

        //加载组件配置
        Config::load(MEDIAMANAGE_DIR."config.php",'','mediamanage') ;

        //缓存设置
        $cache_config = Config::get("cache",'mediamanage');
        cache($cache_config);

        //参数过滤检测
        $sysconfig_default_filter = Config::get("default_filter");
        if(!$sysconfig_default_filter){
            $default_filter =  Config::get("default_filter",'mediamanage');

            $this->request->filter($default_filter);
        }

		$this->param        = $this->request->param();
		$this->module       = $this->request->module();
		$this->controller   = $this->request->controller();
		$this->action       = $this->request->action();
		
	}
	
	/**
	 * 加载控制器方法
	 * @access public
	 * @param  string $controller 控制器
	 * @param  string $action 方法名
	 * @return mixed
	 */
	public function autoload($controller,$action){
		$className = '\\mediamanage\\controller\\'.$controller;
		
		$controller = new $className($this->request);

		if(method_exists($controller,$action)){
			
			return  call_user_func([$controller, $action]);
		}
	
		return false;
	}
	
	
}


include_once  MEDIAMANAGE_DIR . 'extend/common.php';
