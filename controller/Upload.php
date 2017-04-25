<?php
namespace mediamanage\controller;
use mediamanage\controller\Base;
use mediamanage\model\MediamanageCategory;
use mediamanage\model\MediamanageUpload;
use mediamanage\model\Mediamanage;
use think\Config;

class Upload extends Base
{
	function __construct($request){
		parent::__construct($request);
		
		$token = $this->request->token('__token__', 'sha1');
		$this->data = array_merge($this->data,["token"=>$token]);
	}
	
	/**
	 * @desc   上传excel | 展示
	 * @return multitype:number string
	 */
	Public function Index(){
		
		if($this->request->isPost()){
			$url = url("upload/DealFiles",array('token'=>$this->request->param('__token__'),'cateid'=>$this->request->param('parent_id')));
			return ['code'=>0,'msg'=>'正在处理...','url'=>$url];
		}else{
			$model_MediamanageCategory = new MediamanageCategory();
			$res = $model_MediamanageCategory->Alls(false)->toArray();
			if(!$res){

                return ['code'=>0,'msg'=>$res['info']];

			}
			$tree = self::Tree($res,0);

			return [MEDIAMANAGE_VIEW_PATH.'index/add.php',array_merge($this->data,['selectCategorys'=>$tree])];
			//return ['code'=>11,'msg'=>'test'];
		}

	}
	
	/**
	 * @desc 　　对带有token的数据进行处理,使用iframe+文件缓存数组的方式进行模拟队列,默认key为０进行第一次处理，之后的链接通过传参key来指定到对应队列数据,每次处理一条数据进行出列
	 * @return multitype:number string |boolean|multitype:number
	 */
	public function DealFiles(){
		
		$token = $this->request->param('token');
		$index   = intval($this->request->param('key'));
		$category_id =	intval( $this->request->param('cateid'));
		if(empty($token) || $category_id==0){
			return ['code'=>0,'msg'=>'非法请求.'];
			return false;
		}
		$model_MediamanageUpload = new MediamanageUpload();
		if($index>0){
			$conllection = cache("MediamanageUpload_".$this->uid.$token);
			//print_r($conllection);
			if(array_key_exists($index, $conllection)){
				echo "正在处理文件".($index+1)."...";
				
				if(array_key_exists($index, $conllection)){
					//读取上传文件内容，print_r($conllection[$index]);
					$res = $this->_DealFiles($conllection[$index],$category_id); //处理信息
					if($res){
						$update_data = [
						'had'=>1,
						'category_id'=>$category_id,
						];
						$update_where = [
						'token'=>$token
						];
						$model_MediamanageUpload->update($update_data,$update_where);
					}
						
					unset($conllection[$index]);
					cache("MediamanageUpload_".$this->uid.$token,$conllection);
				}
				
				unset($conllection[$index]);
				cache("MediamanageUpload_".$this->uid.$token,$conllection);
				@header("refresh:2;url=".url('upload/DealFiles',['token'=>$token,'key'=>$index+1,'cateid'=>$category_id]));
				return ['code'=>($index+1)];
			}else{
				cache("MediamanageUpload_".$this->uid.$token,$conllection,null);
				echo "处理完毕.";
				return [MEDIAMANAGE_VIEW_PATH.'index/dealfiles.php',array_merge($this->data)];
			}
			
		}else{
			//第一次处理
			$conllection = $model_MediamanageUpload->SelectByToken($token);
			$conllection = collection($conllection)->toArray();
			
			if($conllection && $conllection[0]['had']==0){
				echo "正在处理文件".($index+1)."...";
				if(array_key_exists($index, $conllection)){
					//读取上传文件内容，print_r($conllection[$index]);
					$res = $this->_DealFiles($conllection[$index],$category_id); //处理信息
					if($res){
						$update_data = [
							'had'=>1,
							'category_id'=>$category_id,
						];
						$update_where = [
							'token'=>$token
						];
						$model_MediamanageUpload->update($update_data,$update_where);
						
						unset($conllection[$index]);
						cache("MediamanageUpload_".$this->uid.$token,$conllection);
						
						@header("refresh:2;url=".url('upload/DealFiles',['token'=>$token,'key'=>$index+1,'cateid'=>$category_id]));
						return ['code'=>1];
					}else{
						
						return ['code'=>0,'msg'=>'数据出错.[请检测数据头是否符合.]'];
					}
				}

				
			}else if($conllection && $conllection[0]['had']==1){
				return ['code'=>0,'msg'=>'请注意此文件已处理.'];
				
			}else{
				return ['code'=>0,'msg'=>'请上传文件.'];
			}
			
		}


		//return ['code'=>1,'msg'=>'正在处理'];
	}
	
	/**
	 * @param $arr  数据集
	 * @param $category_id 栏目id
	 * @desc 处理表格,将数据插入到数据库
	 */
	private function _DealFiles($arr,$category_id){

		//print_r($pFilename);
		//print_r($upload_id);
		//$pFilename = str_replace('.\\', ROOT_PATH, str_replace( '/',DS,$arr['path'] ));//还原文件路径
		$pFilename = WEB_PUBLIC.$arr['path'];
        $upload_id = $arr['id'];
	
		if(is_file($pFilename) && in_array($arr['filetype'], ['xls','xlsx'])){
			
			//$phpexcel_reader = \PHPExcel_IOFactory::createReader(); // 读取 excel 文档
			$reader = \PHPExcel_IOFactory::load($pFilename);
			$objWorksheet = $reader->getSheet(0);
			$highestRow = $objWorksheet->getHighestRow(); // 取得总行数
			$highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
			$highestColumn2= \PHPExcel_Cell::columnIndexFromString($highestColumn); //字母列转换为数字列
			//$arr = array(0=> '0', 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
			// 一次读取一列
			$res = array();
			
			for ($row = 1; $row <= $highestRow; $row++) {
					
				for ($column = 0; $column < $highestColumn2; $column++) {
					$val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
					//$val = $objWorksheet->getCell($address)->getValue();
					$res[$row-1][$column] = $val;
				}
			}

            //检测头序是否符号标准，不符合直接返回错误
			Config::load(MEDIAMANAGE_DIR."config.header.php",'','mediamanage') ;
            $MEDIAMANAGE_HEADER_ARRAY = Config::get('MEDIAMANAGE_HEADER_ARRAY','mediamanage');
            $zero_header_arr = $res[0];
            $zero_header_arr = array_slice($zero_header_arr,0,10);//取10个
            $zero_header_str = implode("|",$zero_header_arr);
            if(!in_array($zero_header_str,$MEDIAMANAGE_HEADER_ARRAY)){
                return false;
            }

			
			$data=[];
			foreach ($res as $key=>$value){
				$data[$key]['key'] = $key;
				$data[$key]['value'] = serialize($value);
				$data[$key]['upload_id'] = $upload_id;
				$data[$key]['category_id'] = $category_id;
			}

			//echo json_encode( ['a'=>'b'] );
			$model_Mediamanage = new Mediamanage();
			$res = $model_Mediamanage->Add($data);
			if($res["status"]){
				return true;
			}else{
				return false;
			}
			//$s = $this->_FormatArr($res);
		}else{
			return false;
		}

	}


    /**
     * @desc   ajax 上传excel-xls，并对文件进行分析，插入数据库
     * @return array
     */
	Public function AjaxUpload(){
	    //
        $category_id = $this->request->post('category_id');
        $category_id = intval($category_id);

		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		
		//设置验证规则
		$rule =  Config::get("upload");
		//print_r($rule);
		$file->validate($rule);
		
		//print_r($file->getInfo());
		$fileinfo = $file->getInfo();
		// 移动到框架应用根目录/public/uploads/ 目录下::  mediamanage/分类id/用户id/
		$upload_dir = MEDIAMANAGE_UPLOAD_PATH .$category_id .DS . $this->uid;
		if(!is_dir($upload_dir)){
            mkdirs($upload_dir);
		}
		
		$info = $file->move($upload_dir,true,false);
		if($info){
			// 成功上传后 获取上传信息
			$res = [
                'filename'=>$info->getFilename(),
                'savename'=>$info->getSaveName(),
                'filetype'=>$info->getExtension(),
                'filetype2'=>$fileinfo['type'],
                'filesize'=>$file->getSize(),
                'name'=>$fileinfo['name'],
			];
			
			//插入数据库进行管理
			$data = $res;
			$data['path'] 	= str_replace('\\','/',str_replace(ROOT_PATH . 'public' . DS, '/', $upload_dir)).'/'.str_replace('\\', '/', $data['savename']);
			$data['uid'] 	= $this->uid;
			$data['token'] 	= $this->request->param('token');
			
			//print_r($data);
			$model_MediamanageUpload = new MediamanageUpload();
			$res = $model_MediamanageUpload->Add($data);
			if(!$res['status']){
				return ['code'=>0,'msg'=>$file->getError(),'status'=>0,'error'=>$res['info']];
			}
			return ['code'=>1,'msg'=>$res,'status'=>1,'info'=>$res['info']];
		}else{
			// 上传失败获取错误信息
			return ['code'=>0,'msg'=>$file->getError(),'status'=>0,'error'=>$file->getError()];
		}
		
	}

    /**
     * @desc  文件管理 列表
     */
	public function Manage(){
        //获取相关类型的内容
        $category_id = $this->request->param('category_id');
        $category_id = intval($category_id);
        $model_MediamanageCategory = new MediamanageCategory();
        $array_list_category = $model_MediamanageCategory->Alls(true)->toArray();
        //print_r( $return_list );

        //获取对应的文件
        if($category_id==0){$pagesize = 12;}else{$pagesize = 15;}
        $model_MediamanageUpload = new MediamanageUpload();
        $obj_list = $model_MediamanageUpload->Lists($category_id,$pagesize);
        $array_render = $obj_list['render']->toArray();

        $template_data = array_merge(
            $this->data,
            ['category_id'=>$category_id],
            ['list_category'=>$array_list_category],
            ['list_data'=>$array_render["data"]],
            ['page'=>$obj_list["page"]]
        );
        return [MEDIAMANAGE_VIEW_PATH.'index/manage.php',$template_data];
    }

    /**
     * @desc  删除对应的媒体库文件，关联删除 数据库内容 文件
     * @return array
     */
    public function Delete(){
        $upload_id = $this->request->param("upload_id");
        $upload_id = intval($upload_id);
        $upload_path =  $this->request->param("path");
        $file_path =  WEB_PUBLIC.base64_decode($upload_path);

        if( $upload_id && is_file($file_path) ){

            $model_MediamanageUpload = new MediamanageUpload();
            $res = $model_MediamanageUpload->where('id',$upload_id)->delete();
            if($res){
                //删除文件
                @unlink( $file_path );

                //删除对应数据库信息
                $model_Mediamanage = new Mediamanage();
                $model_Mediamanage->where('upload_id',$upload_id)->delete();

                return ['code'=>1,'msg'=>'删除成功.','url'=>'history.back(-1)'];
            }
        }

        return ['code'=>0,'msg'=>'删除失败，请联系管理员.'];
    }
}