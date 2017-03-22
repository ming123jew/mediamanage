<?php
namespace mediamanage\controller;

class Index
{
	public function __construct($request)
	{
		$this->request  = $request;
		$this->param    = $this->request->param();
		$this->post     = $this->request->post();
		$this->id       = isset($this->param['id'])?intval($this->param['id']):'';
		$this->data     = ['pach'=>VIEW_PATH];
	}
	/**
	 * 列表
	 */
	function Index(){
		$pFilename = "F:\\2017\\huiz_crm_v1.0\\data\\c.xlsx";
		if(is_file($pFilename)){
			
		}
		//$phpexcel_reader = \PHPExcel_IOFactory::createReader(); // 读取 excel 文档
		$reader = \PHPExcel_IOFactory::load($pFilename);
		$objWorksheet = $reader->getSheet(1);
		$highestRow = $objWorksheet->getHighestRow(); // 取得总行数
		$highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
		$highestColumn2= \PHPExcel_Cell::columnIndexFromString($highestColumn); //字母列转换为数字列
	
		$arr = array(0=> '0', 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
		// 一次读取一列
		$res = array();

		for ($row = 1; $row <= $highestRow; $row++) {
			
			for ($column = 0; $column < $highestColumn2; $column++) {
				$val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
				//echo $val = $objWorksheet->getCell($address)->getValue();
				$res[$row-1][$column] = $val;
			}
		}
		//print_r($res);
		$s = $this->_FormatArr($res);
		//$this->assign("list",$s);
		//$this->assign("num_row",$highestRow);
		//return $this->fetch();
		return [MEDIAMANAGE_VIEW_PATH.'index/index.php',array_merge($this->data,['num_row'=>$highestRow,'list'=>$s])];
	}
	
	//格式化二维数组，转化成表格输出
	private function _FormatArr($arr){
		$s = '<table class="table table-hover table-bordered">';
		$s1="";
		$s2="";
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
	
	    $s .= $s1.'<tbody>'.$s2.'</tbody>';
	    $s .= '</table>';
	    return $s;
	}

}
