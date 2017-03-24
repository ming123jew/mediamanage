<?php
namespace mediamanage\validate;

use think\Validate;
class MediamanageCategory extends Validate
{
	protected $rule = [
		'name'  =>  'require',
	];

	protected $message = [
		'name.require'  =>  '媒体分类名称必须',
	];

	protected $scene = [
		'add'   =>  ['name'],
		'edit'  =>  ['name'],
	];
}