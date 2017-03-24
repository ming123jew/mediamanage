<?php
namespace mediamanage\model;
use think\Loader;
class MediamanageCategory extends Base
{
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}
	
    // 设置完整的数据表（包含前缀）
    protected $name = 'mediamanage_category';


    public function Lists($pagesize=10){
    	
    	//如启用mongodb则使用mongodb
    	if($this->mongodb){
    		$res = $this->mongodbLists($pagesize);
    		return $res;
    		exit(0);
    	}
    	 
    	//mysql
    	// 查询状态为1的用户数据 并且每页显示10条数据
    	$list = self::where('status',1)->paginate($pagesize);
    	// 获取分页显示
    	$page = $list->render();
    	 
    	$res['list'] = $list;
    	$res['page'] = $page;
    	return $res;
    }
    private function mongodbLists($pagesize=10){
    	$list = $this->mongodb->name($this->name)->paginate($pagesize);
    	// 获取分页显示
    	$page = $list->render();
    	
    	$res['list'] = $list;
    	$res['page'] = $page;
    	return $res;
    }
	

    
    /**
     * @desc   支持批量添加
     * @param  [] $data
     * @return Ambigous <multitype:, \think\false, boolean, multitype:Ambigous <\think\$this, \mediamanage\model\MediamanageCategory> >
     */
    public function Add($data=[]){
    	
    	//如启用mongodb则使用mongodb
    	if($this->mongodb){
    		$res = $this->mongodbAdd($data);
    		return $res;
    		exit(0);
    	}
    	
    	//mysql
    	$res=[];
    	if (is_array($data)){
    		$datas = [$data];
    		$info = $this->validate('mediamanage\\validate\\MediamanageCategory')->saveAll($datas);
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
    private function mongodbAdd($data=[]){
    	$res=[];
    	if (is_array($data)){
    		$datas = [$data];
    		//mongodb不支持->validate
    		//手动验证
    		$validate = Loader::validate('mediamanage\\validate\\MediamanageCategory');
    		if(!$validate->check($data)){
    			$res["status"] = false;
    			$res["info"] = $validate->getError();
    			
    		}else{
    			$info = $this->mongodb->name($this->name)->insertAll($datas);
    			if(false === $info){
    				// 验证失败 输出错误信息
    				$info = $this->getError();
    				$res["status"] = false;
    				$res["info"] = $info;
    			
    			}else{
    				$res["status"] = true;
    				$res["info"] = $info;
    			}	
    		}

    	}else{
    		$res["status"] = false;
    		$res["info"] = 'data is not array.';
    	}
    	return $res;
    }
    
    
    public function Find($id){
    	if ($id){
    		$info = $this->get(['id'=>$id]);
    		
    		if(!$info){
    			$res["status"] = false;
    			$res["info"] = 'not exist';
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
    
    /**
	 * @desc   支持批量更新（依赖主键）
	 * @param  [] $data
     */
    public function Edit($data=[]){
    	if (is_array($data)){
    		$datas = [$data];
    		$res = $this->validate('mediamanage\\validate\\MediamanageCategory')->saveAll($datas);
    		if(false === $res){
    			// 验证失败 输出错误信息
    			$res["status"] = false;
    			$res["info"] =  $this->getError();
    			
    		}else{
    			$res["status"] = true;
    			$res["info"] = $res;
    		}
    	}else{
    		$res["status"] = false;
    		$res["info"] = 'data is not array.';
    	}
    	return $res;
    }
    
    /**
	 *  @desc   支持批量删除
	 *  @param  [] $data
     */
    public function Delete($data=[]){
    	if (is_array($data)){
    		return self::destroy($data);
    	}else{
    		return false;
    	}
    }
    

}
?>