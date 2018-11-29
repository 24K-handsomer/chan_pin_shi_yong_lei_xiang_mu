<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Loader;


/**/
function excelFileExport($title,$data,$header,$letterarr){
	/*$data = array(
	array('王城', '男', '18', '1997-03-13', '18948348924'),
	array('李飞虹', '男', '21', '1994-06-13', '159481838924'),
	array('王芸', '女', '18', '1997-03-13', '18648313924'),
	array('郭瑞', '男', '17', '1998-04-13', '15543248924'),
	array('李晓霞', '女', '19', '1996-06-13', '18748348924'),
	);*/

	Loader::import('PHPExcel.Classes.PHPExcel');
	Loader::import('PHPExcel.Classes.PHPExcel.php');
	//Loader::import('PHPExcel.Classes.PHPExcel.Writer/Excel2007.php');
	Loader::import('PHPExcel.Classes.PHPExcel.IOFactory');
	//或者include 'PHPExcel/Writer/Excel5.php'; 用于输出.xls的
	// 创建一个excel
	//实例化excel类
    $excelObj = new PHPExcel();
    //构建列数--根据实际需要构建即可
    //$letter = array('A', 'B', 'C', 'D', 'E', 'F','G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O', 'P', 'Q', 'R'，'S', 'T', 'U', 'V', 'W', 'X','Y', 'Z');
    $letter = $letterarr;
    //表头数组--需和列数一致
    //$tableheader = array('学号','姓名', '年龄', '性别');
    $tableheader = $header;

    $objActSheet = $excelObj->getActiveSheet();
    //填充表头信息
    for ($i = 0; $i < count($tableheader); $i++) {
        $objActSheet->setCellValue("$letter[$i]1", "$tableheader[$i]");
    }
    unset($i);
    //循环填充数据
    foreach ($data as $k => $v) {
        $num = $k + 1 + 1;
        //设置每一列的内容
        for($i = 0; $i < count($letter); $i++){
        	$objActSheet->setCellValue($letter[$i].$num,$v[$i]);
        }
        //设置行高
        //$objActSheet->getRowDimension($k+4)->setRowHeight(30);
    }
    //以下是设置宽度
    /*$excelObj->getActiveSheet()->getColumnDimension('A')->setWidth(46);
    $excelObj->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $excelObj->getActiveSheet()->getColumnDimension('C')->setWidth(10);
    $excelObj->getActiveSheet()->getColumnDimension('D')->setWidth(20);*/
    
    //设置表头行高
    /*$excelObj->getActiveSheet()->getRowDimension(1)->setRowHeight(28);
    $excelObj->getActiveSheet()->getRowDimension(2)->setRowHeight(28);
    $excelObj->getActiveSheet()->getRowDimension(3)->setRowHeight(28);*/

    //设置居中
    //$excelObj->getActiveSheet()->getStyle('A1:D1'.($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //所有垂直居中
    //$excelObj->getActiveSheet()->getStyle('A1:D1'.($k+2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    //设置字体样式
    /*$excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setName('黑体');
    $excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setSize(20);
    $excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
    $excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);  
    $excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setName('宋体');
    $excelObj->getActiveSheet()->getStyle('A1:D1')->getFont()->setSize(16);
    $excelObj->getActiveSheet()->getStyle('A1:D1'.($k+2))->getFont()->setSize(10);*/

    //设置自动换行
    //$excelObj->getActiveSheet()->getStyle('A1:D1'.($k+2))->getAlignment()->setWrapText(true);

    // 重命名表
    $filename = iconv("utf-8", "gb2312", $title); 

    // 设置下载打开为第一个表
    //$excelObj->setActiveSheetIndex(0); 

    //设置header头信息
    ob_end_clean();//清除缓冲区,避免乱码 
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0
	$objWriter = new PHPExcel_Writer_Excel5($excelObj);
	$objWriter->save('php://output');
    /*exit();*/

}



function splitName($fullname){ 
    $hyphenated = array('欧阳','太史','端木','上官','司马','东方','独孤','南宫','万俟','闻人','夏侯','诸葛','尉迟','公羊','赫连','澹台','皇甫','宗政','濮阳','公冶','太叔','申屠','公孙','慕容','仲孙','钟离','长孙','宇文','城池','司徒','鲜于','司空','汝嫣','闾丘','子车','亓官','司寇','巫马','公西','颛孙','壤驷','公良','漆雕','乐正','宰父','谷梁','拓跋','夹谷','轩辕','令狐','段干','百里','呼延','东郭','南门','羊舌','微生','公户','公玉','公仪','梁丘','公仲','公上','公门','公山','公坚','左丘','公伯','西门','公祖','第五','公乘','贯丘','公皙','南荣','东里','东宫','仲长','子书','子桑','即墨','达奚','褚师'); 
    $vLength = mb_strlen($fullname, 'utf-8'); 
    $lastname = ''; 
    $firstname = '';//前为姓,后为名 
    if($vLength > 2){ 
        $preTwoWords = mb_substr($fullname, 0, 2, 'utf-8');//取命名的前两个字,看是否在复姓库中 
        if(in_array($preTwoWords, $hyphenated)){ 
            $lastname = $preTwoWords; 
            $firstname = mb_substr($fullname, 2, 10, 'utf-8'); 
        }else{ 
            $lastname = mb_substr($fullname, 0, 1, 'utf-8'); 
            $firstname = mb_substr($fullname, 1, 10, 'utf-8'); 
        } 
    }else if($vLength == 2){//全名只有两个字时,以前一个为姓,后一下为名 
        $lastname = mb_substr($fullname ,0, 1, 'utf-8'); 
        $firstname = mb_substr($fullname, 1, 10, 'utf-8'); 
    }else{ 
        $lastname = $fullname; 
    } 
    return array($lastname, $firstname); 
}