<?php
namespace mediamanage\controller;

use mediamanage\controller\Base;
use mediamanage\model\Mediamanage;
use mediamanage\model\MediamanageCategory;

class Index extends Base
{
	function __construct($request){
		parent::__construct($request);
	}

    /**
     * @desc   列表内容
     * @return array
     */
	public function Index(){
	    $model_MediamanageCategory = new MediamanageCategory();
        $array_list_category = $model_MediamanageCategory->Alls(true)->toArray();

        //获取相关类型的内容
        $category_id = $this->request->param('category_id');
        $category_id = intval($category_id);
        $page_size = 10;
        $string = '内容为空.';
        $info = [ 'page'=>'' ];
        if($category_id){
            $model_Mediamanage = new Mediamanage();
            $info = $model_Mediamanage->Lists($category_id,$page_size);
            $info_render = $info['render']->toArray();
            if ($info_render['data']){
                $return_list = $info_render['data'];

                $string = '<table class="table table-hover table-bordered">';
                //对内容进行--划分
                $arr_divided = ( self::_divided($return_list,1) );
                //print_r($arr_divided['data_head'] );
                //头部内容  序号  项目  姓名...
                $str_head = '';
                //print_r($arr_divided['data_head']);
                foreach ($arr_divided['data_head'] as $k=>$val){
                    foreach ($val as $v){
                        $str_head .= $this->_FormatArr($v,1);
                    }
                }
                $string .= $str_head;
                //列表内容   xx   xxx   xx
                $str_data = '';
                foreach ($arr_divided['data_list'] as $k=>$val){
                    foreach ($val as $v){
                        $str_data .= $this->_FormatArr($v);
                    }
                }
                $string .= $str_data;
                $string .= '</table>';
            }
        }

	    $template_data = array_merge(
	        $this->data,
            ['category_id'=>$category_id],
            ['list_category'=>$array_list_category],
            ['page'=>$info['page']],
            ['string_data'=>$string]
        );
        return [MEDIAMANAGE_VIEW_PATH.'index/index.php',$template_data];
    }

    /**
     * @desc  将数组分组
     */
    private function _divided($arr,$cache=0){
        $data_head = [];
        foreach ($arr as $key=>&$value){
            $cache_key =  'mediamanage_controller_index_divided_'.$value['category_id'].'_'.$value['upload_id'];
            if($value['key']==0){
                $data_head[$value['category_id']][$value['upload_id']][] = unserialize($value['value']);
                //缓存头
                if(!empty($cache)){
                    $cahce_value = [
                        $value['category_id']=>[
                                $value['upload_id']=>[
                                    0=>unserialize($value['value'])
                                ]
                            ]
                    ];
                    cache($cache_key,$cahce_value);
                }
               // $data_list[$value['category_id']][$value['upload_id']][] = unserialize($value['value']);
               //$data_list[$value['category_id']][$value['upload_id']]['menu'] = [0=>1];
            }else{
                //对数据进行分组
                $data_list[$value['category_id']][$value['upload_id']][] = unserialize($value['value']);
            }
            //print_r($value);
        }
        //如果是第二页，则加载缓存头
        if(empty( $data_head) && $cache){
            $data_head = cache($cache_key);

        }
        $data = [
            'data_head'=>$data_head,
            'data_list'=>$data_list
        ];
        return $data;
    }

	//重新定义输出页面，以下方法暂时废弃

	/**
	 * 列表 - 已废弃不用
	 */
	function IndexBak(){
		$catetory_id = intval($this->request->param('cateid'));
		$model_Mediamanage = new Mediamanage();
		$info = $model_Mediamanage->Lists($catetory_id,20);
		$array = [];
		if($info['list']){
			$array = collection($info['list'])->toArray();
		}
		
		//对数据进行一次格式化，以便输出表格
		//缓存数据头

		$data_head = [];
		foreach ($array as $key=>$value){
			if($value['key']==0){
				$data_head[$value['category_id']][$value['upload_id']] = ($value['value']);
				$data_list[$value['category_id']][$value['upload_id']][] = unserialize($value['value']);
				$data_list[$value['category_id']][$value['upload_id']]['menu'] = [0=>1];
			}else{
				//对数据进行分组
				$data_list[$value['category_id']][$value['upload_id']][] = unserialize($value['value']);
			}
			//print_r($value);
		}
		$cache_key = "Mediamanage_Index_DataHead".$catetory_id;
 		$cache_data_head = cache($cache_key);
 		//$cache_data_head = unserialize($cache_data_head);
 		//print_r($data_head);
		if($cache_data_head && is_array($cache_data_head)){
			//echo "here";
			$array_merge = array_merge($cache_data_head,$data_head);
			$cache_data_head_new =	$this->_array_unique_fb($array_merge);
			
			cache($cache_key,$cache_data_head_new);
		}else{
			cache($cache_key,$data_head);
		} 
		print_r($data_head);
		print_r($data_list);
		//array_unshift  头部插入
		$str = '';
		foreach ($data_list as $k=>$val){
			foreach ($val as $v){
				if( !array_key_exists('menu', $v) ){
					echo "no";
					array_unshift($v);
				}
				$str .= $this->_FormatArr($v);
			}	
		}

		return [MEDIAMANAGE_VIEW_PATH.'index/index.php',array_merge($this->data,['list'=>$str,'page'=>$info['page']])];
	}
	//二维数组去掉重复值
	private function _array_unique_fb($array2D){
		foreach ($array2D as $v){
			$v=join(',',$v); //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
			$temp[]=$v;
		}
		$temp=array_unique($temp); //去掉重复的字符串,也就是重复的一维数组
		foreach ($temp as $k => $v){
			$temp[$k]=explode(',',$v); //再将拆开的数组重新组装
		}
		return $temp;
	}

    /**
     * @desc 格式化数组，转化成表格输出
     * @param $arr          传入的多维数组
     * @param int $head     头序标识
     * @param int $i        打印前10个数据
     * @return string       返回字符串
     */
	private function _FormatArr($arr,$head=0,$i=10){
		//unset($arr['menu']);
		$s = '';
		$s1="";
		$s2="";
		//print_r($arr);
		foreach ($arr as $key => $value){

			if($head==1){
				
				$s1 .= '    <thead>';
				$s1 .= '    <tr>';
                $i = 0;
                $style = '';
				foreach ($value as $k=>$v){
                    if($i>=9){
                        $style = 'style="display:none;"';
                    }
				    $s1 .= '        <th width="50" '.$style.'>'.$v.'</th>';
                    $i++;
				}
				$s1 .= '    </tr>';
				$s1 .= '    </thead>';
			}else{
				$s2 .= '    <tr>';
                $i = 0;
                $style = '';
				foreach ($value as $k=>$v){
                    if($i>=9){
                        $style = 'style="display:none;"';
                    }
				    $s2 .= '        <td '.$style.'>'.$v.'</td>';
                    $i++;
				}
				$s2 .= '    </tr>';
			}

		}
	
	    $s .= $s1.'<tbody>'.$s2.'</tbody>';
	    return $s;
	}

    /**
     * @desc  查看单个文档相关内容
     */
	public function ShowFile(){
	    $path = $this->request->param("path");

        $file_path =  WEB_PUBLIC.base64_decode($path);
        $str_xls = '';
        if(is_file($file_path)){
            $array_xls = readXls($file_path);

            $str_xls = self::_FormatArr2($array_xls);
        }
        if(!$str_xls){$str_xls="文件内容为空!";}
        $template_data = array_merge(
            $this->data,
            ['str_xls'=>$str_xls]
        );
        return [MEDIAMANAGE_VIEW_PATH.'index/showfile.php',$template_data];
    }
    //格式化二维数组，转化成表格输出
    private function _FormatArr2($arr){
        //unset($arr['menu']);
        if (count($arr)<2){return false;}
        $s = '<table class="table table-hover table-bordered">';
        $s1="";
        $s2="";
        //print_r($arr);
        foreach ($arr as $key => $value){

            if($key==0){

                $s1 .= '    <thead>';
                $s1 .= '    <tr>';
                foreach ($value as $k=>$v){
                    $s1 .= '        <th width="50">'.$v.'</th>';
                }
                $s1 .= '    </tr>';
                $s1 .= '    </thead>';
            }else{
                $s2 .= '    <tr>';
                foreach ($value as $k=>$v){
                    $s2 .= '        <td >'.$v.'</td>';
                }
                $s2 .= '    </tr>';
            }
        }

        $s .= $s1.'<tbody>'.$s2.'</tbody></table>';
        return $s;
    }
}
