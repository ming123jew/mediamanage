<?php
namespace mediamanage\controller;
use think\Config;
class Base {
	
	const  PATH                 = __DIR__;
	public $request				= "";
	public $param				= "";
	public $post				= "";
	public $id					= 0 ;
	public $data				= "";
	public $user				= "";
	public $uid					= 0;
	
	
	//权限
	public $MEDIAMANAGE_role_table_name = "";
	public $MEDIAMANAGE_role_id = "";
	public $MEDIAMANAGE_role_name = "";
	public $MEDIAMANAGE_admin_table_name = "";
	public $MEDIAMANAGE_admin_role = "";
	
	public function __construct($request)
	{	
		//加载组件配置
		Config::load(MEDIAMANAGE_DIR."config.php") ;
		
		//如果设置了session信息则,读取session
		$MEDIAMANAGE_admin_session = Config::get("MEDIAMANAGE_admin_session");
		if($MEDIAMANAGE_admin_session){
			$this->user = session($MEDIAMANAGE_admin_session);
		}
		$MEDIAMANAGE_admin_uid = Config::get("MEDIAMANAGE_admin_uid");
		if($MEDIAMANAGE_admin_uid && !array_key_exists('uid',$this->user)){
			$this->uid = $this->user[$MEDIAMANAGE_admin_uid];
			$this->user['uid'] = $this->uid;
		}else{
			$this->uid = $this->user['uid'];
		}
		
		$this->request  = $request;
		$this->param    = $this->request->param();
		$this->post     = $this->request->post();
		$this->id       = isset($this->param['id'])?intval($this->param['id']):'';
		$this->data     = ['pach'=>MEDIAMANAGE_VIEW_PATH];
	}
	
}