<?php
namespace mediamanage\validate;

use think\Validate;
class Mediamanage extends Validate
{
	
	// 定义时间戳字段名
	//protected $createTime = 'create_time';
	//protected $updateTime = 'update_time';
	protected $rule = [
		'key'  =>  'require',//文件名
		'value'  =>  'require',//文件名
		'upload_id'  =>  'require',
		
	];

	protected $message = [
		'key.require'  =>  'key必须',
		'value.require'  =>  'value必须',
		'upload_id.require'  =>  'upload_id必须',
	];

	protected $scene = [
		'_DealFiles'   =>  ['key','value','upload_id'],

	];
}