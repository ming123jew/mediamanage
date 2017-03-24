<?php
namespace mediamanage\validate;

use think\Validate;
class MediamanageAccess extends Validate
{
	protected $rule = [
		'category_id'  =>  'require',
		'role'  =>  'require',
		'action'  =>  'require',
	];

	protected $message = [
		'category_id.require'  =>  'category_id必须',
		'role.require'  =>  'role必须',
		'action.require'  =>  'action必须',
	];

	protected $scene = [
		'add'   =>  ['category_id','role','action'],
		'edit'  =>  ['category_id','role','action'],
	];
}