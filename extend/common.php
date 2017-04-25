<?php
/**
 * Created by PhpStorm.
 * User: ming123jew
 * Date: 2017/4/13
 * Time: 12:02
 */

/**
 * @desc  n层创建文件夹
 * @param $dir
 * @return bool
 */
function mkdirs($dir){
    if(!is_dir($dir)){
        if(!mkdirs(dirname($dir))){
            return false;
        }
        if(!mkdir($dir,0777)){
            return false;
        }
    }
    return true;
}

/**
 * @desc  获取xls内容
 * @param $pFilename
 * @return array|bool
 */
function readXls($pFilename){

    $arr_pFilename = explode($pFilename,'.');
    $is_xls = strstr($pFilename,".xls");

    if(is_file($pFilename) && in_array($is_xls, ['.xls','.xlsx'])) {

        //$phpexcel_reader = \PHPExcel_IOFactory::createReader(); // 读取 excel 文档
        $reader = \PHPExcel_IOFactory::load($pFilename);
        $objWorksheet = $reader->getSheet(0);
        $highestRow = $objWorksheet->getHighestRow(); // 取得总行数
        $highestColumn = $objWorksheet->getHighestColumn(); // 取得总列数
        $highestColumn2 = \PHPExcel_Cell::columnIndexFromString($highestColumn); //字母列转换为数字列
        //$arr = array(0=> '0', 1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z');
        // 一次读取一列
        $res = array();

        for ($row = 1; $row <= $highestRow; $row++) {

            for ($column = 0; $column < $highestColumn2; $column++) {
                $val = $objWorksheet->getCellByColumnAndRow($column, $row)->getValue();
                //$val = $objWorksheet->getCell($address)->getValue();
                $res[$row - 1][$column] = $val;
            }
        }
        //print_r($res);
        return $res;
    }
    return false;
}