<?php
namespace mediamanage\model;

class MediamanageUpload extends Base
{
	protected $autoWriteTimestamp = true;
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}

	// 设置完整的数据表（包含前缀）
	protected $name = 'mediamanage_upload';


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
	 * 
	 * @param  $token
	 * @return string
	 */
	public function SelectByToken($token){
		
		if ($token){
			$where = ['token'=>$token];
			$info = $this->all($where);
		}
		return $info;
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
		if (is_array($data)){
			
			$info = $this->validate('mediamanage\\validate\\MediamanageUpload')->saveAll($datas);
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