<?php
namespace mediamanage\model;
use think\Db;
use think\Config;


class Base extends \think\Model{

	
	public $mongodb = "";
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
		$db_type = Config::get('MEDIAMANAGE_db_config1.type');
		if($db_type=='\think\mongo\Connection'){
			$this->mongodb = Db::connect("MEDIAMANAGE_db_config1");
		}
	}
	
	

}