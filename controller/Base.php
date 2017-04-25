<?php
namespace mediamanage\controller;
use think\Config;
use mediamanage\library\Tree;
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

		//如果设置了session信息则,读取session
		$MEDIAMANAGE_admin_session = Config::get("MEDIAMANAGE_admin_session",'mediamanage');
		if($MEDIAMANAGE_admin_session){
			$this->user = session($MEDIAMANAGE_admin_session);
		}
		$MEDIAMANAGE_admin_uid = Config::get("MEDIAMANAGE_admin_uid",'mediamanage');
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
		$this->data     = ['pach'=>MEDIAMANAGE_VIEW_PATH,'static'=>MEDIAMANAGE_STYLE_PATH];
	}

    public function Tree($datas,$selected = 1,$field='id'){
        $tree = new Tree();
        $array = [];

        foreach ($datas as $r) {
            $r['selected'] = $r[$field] == $selected ? 'selected' : '';
            $array[] = $r;
        }
        $str = "<option value='\$id' \$selected>\$spacer \$name</option>";

        $tree->init($array);
        $parentid = isset($where['parentid'])?$where['parentid']:0;

        return $tree->get_tree($parentid, $str);
    }


}