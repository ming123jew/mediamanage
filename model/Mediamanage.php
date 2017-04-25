<?php
namespace mediamanage\model;

use think\Db;
class Mediamanage extends Base
{
	protected $autoWriteTimestamp = true;
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}

	// 设置完整的数据表（包含前缀）
	protected $name = 'mediamanage';
	

	/**
	 * 列表
	 * @param number $pagesize
	 * @return array
	 */
	public function Lists($catetory_id=0,$pagesize=10){
		 
		//mysql
		// 查询  并且每页显示10条数据
		if($catetory_id){
			$render = self::where('category_id',$catetory_id)->order('id')->paginate($pagesize);
		}else{
			$render = self::order('id')->paginate($pagesize);
		}
		// 获取分页显示
		$page = $render->render();
	
		$res['list'] = $render->items();
		$res['render'] = $render;
		$res['page'] = $page;
		return $res;
	}
	
	
	/**
	 * @desc   支持批量添加
	 * @param  [] $data
	 * @return Ambigous <multitype:, \think\false, boolean, multitype:Ambigous <\think\$this, \mediamanage\model\MediamanageCategory> >
	 */
	public function Add($data=[]){
		 
		if (count($data) == count($data, 1)) {
			$datas = [$data];//一维数组
		} else {
			
			$datas = $data;//二维数组
		}
		//mysql
		$res=[];
		if (is_array($datas)){
			//性能问题使用拼接
			//$info = $this->saveAll($datas);
			//print_r($datas);
			$table = Db::getTable($this->name);
	
			//500条处理一次，避免内存溢出
			$i = 0;
			$m = 0;
			foreach ($datas as $key=>$value){
				if($i>200){
					$i=0;
					$m = $m + 1;
					$sqlval[$m][] = 'INSERT INTO '.$table.' values(null,'.$value['key'].',\''.$value['value'].'\','.time().','.$value['upload_id'].','.$value['category_id'].'),';		
				}else{
					if($i==0){
						$sqlval[$m][] = 'INSERT INTO '.$table.' values(null,'.$value['key'].',\''.$value['value'].'\','.time().','.$value['upload_id'].','.$value['category_id'].'),';
					}else{
						$sqlval[$m][] = '(null,'.$value['key'].',\''.$value['value'].'\','.time().','.$value['upload_id'].','.$value['category_id'].'),';
					}
					
				}
				$i++;
				//unset($datas[$key]);
			}
			unset($datas);
			//print_r($sqlval);
			//$sql = substr($sql, 0, -1);
			foreach ($sqlval as $k=>$val){
				
				$sql = '';
				foreach ($val as $v){
					$sql .= $v;
				}
				$sql = substr($sql, 0, -1);
				
				$info = $this->query($sql);
				unset($sqlval[$k]);
				
			}
			
			if(false === $info){
				// 验证失败 输出错误信息
				$info = $this->getError();
				$res["status"] = false;
				$res["info"] = $info;
				 
			}else{
				$res["status"] = true;
				$res["info"] = $info;
			}
	
		}else{
			$res["status"] = false;
			$res["info"] = 'data is not array.';
		}
		return $res;
	}
}