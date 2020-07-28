<?php
/**
* PHPExcel
*
* Copyright (C) 2006 - 2014 PHPExcel
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*
* @category   PHPExcel
* @package    PHPExcel
* @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
* @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
* @version    1.8.0, 2014-03-02
*/

/** Error reporting */
//error_reporting(E_ALL);
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


if (PHP_SAPI == 'cli')
    die('Ang gwapo ko - Jundrie');

/** Include PHPExcel */
require_once 'phpexcel/Classes/PHPExcel.php';
include_once('config.php');

$totalcost=0;
$grandtotalcost=0;


$st=date('Y-m-d');
$et=date('Y-m-d');
$pla=' selected="selected"';
$ucntr=0;
$tdata='';
$thdata='';


if(!isset($_GET['startdate'])){
    $_GET['startdate']=date('Y-m-d');
    $_GET['enddate']=date('Y-m-d');
}
function interval( $startdate , $enddate, $type ){
    if($type=='Weekly'){
        if(date('D', strtotime($startdate)) === 'Mon') {
            $startdate=$startdate;
        }
        else{
            $startdate=date('Y-m-d', strtotime('last Monday', strtotime($startdate)));
        }
    }
    if($type=='Monthly'){
        if(date('j', strtotime($startdate)) === '1') {
            $startdate=$startdate;
        }
        else{
            $startdate=$startdate;
        }
    }
    $startdate = strtotime( $startdate );
    $enddate   = strtotime( $enddate );
// New Variables
    $currDate  = $startdate;
    $dayArray  = array();
// Loop until we have the Array
    if($type=='Daily'){
        do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 day' , $currDate );
        } while( $currDate<=$enddate );
    }
    if($type=='Weekly'){
        do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 week' , $currDate );
        } while( $currDate<=$enddate );
    }
    if($type=='Monthly'){
        do{
            $dayArray[] = date( 'Y-m-d' , $currDate );
            $currDate = strtotime( '+1 month' , $currDate );
        } while( $currDate<=$enddate );
    }
// Return the Array
    return $dayArray;
}

$objPHPExcel = new PHPExcel();
$style = array(
        'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        )
    );


$objPHPExcel->getProperties()->setCreator("Jundrie")
    ->setLastModifiedBy("Jundrie")
    ->setTitle("Genset")
    ->setSubject("Genset")
    ->setDescription("Genset")
    ->setKeywords("Genset")
    ->setCategory("Genset");
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '#')->setCellValue('B1', 'Unit');

if(isset($_GET['startdate'])){
    $st=$_GET['startdate'];
    $et=$_GET['enddate'];
    foreach (interval($_GET['startdate'],$_GET['enddate'],"Daily") as $i){
        $arr[$i.'h']=0;
        $arr[$i.'f']=0;
        $arr[$i.'k']=0;
        $arr[$i.'r']=0;
    }
    $total['h']=0;
    $total['f']=0;
    $total['k']=0;
    $total['r']=0;
    $ucntr=0;
    $y = 2;
    $uq=sqlsrv_query($conn,"select * from unit where id in (9,10,11,16,17,18,24,25,26) order by name");
    while($u=sqlsrv_fetch_array($uq)){
        $xx = 2;
        $y++;
        $ucntr++;
    
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$y.'', $ucntr)->setCellValue('B'.$y.'', $u['name']);
        $cth = 0;
        $ctf = 0;
        $ctk = 0;
        $ctr = 0;
      
        foreach(interval($_GET['startdate'],$_GET['enddate'],'Daily') as $x){   

            if($y==3){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, 1, date('M d',strtotime($x)));
                $letter_start = PHPExcel_Cell::stringFromColumnIndex($xx);
                $letter_end = PHPExcel_Cell::stringFromColumnIndex($xx+3);
                $objPHPExcel->setActiveSheetIndex(0)->mergeCells(''.$letter_start.'1:'.$letter_end.'1');
                $objPHPExcel->getActiveSheet()->getStyle(''.$letter_start.'1:'.$letter_end.'1')->applyFromArray($style);

                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, 2, 'RunTime');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+1, 2, 'Fuel ltr');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+2, 2, 'KWH');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+3, 2, 'Reading');
            }

            $mins='0.00';
            $fuel='0.00';
            $kwh='0.00';
            $reading='0.00';
              
            $r=sqlsrv_fetch_array(sqlsrv_query($conn,"select sum(mins) as tmin,sum(fuel) as tfuel,sum(kwh) as tkwh, sum(runStop - runStart) as tkwr from genset_utilizationflatdata where date='".$x."' and unitId='".$u['id']."'"));

            if($r['tmin']>0 || $r['tfuel']>0 || $r['tkwh']>0){

                $mins=number_format(($r['tmin']/60),2);
                $fuel=number_format($r['tfuel'],2);
                $kwh=number_format($r['tkwh'],2);
                $reading=number_format($r['tkwr'],2);

            }
            $cth += $mins;
            $ctf += $fuel;
            $ctk += $kwh;
            $ctr += $reading;

            $arr[$x.'h']+=$mins;
            $arr[$x.'f']+=$fuel;
            $arr[$x.'k']+=$kwh;
            $arr[$x.'r']+=$reading;

            $total['h']+=$mins;
            $total['f']+=$fuel;
            $total['k']+=$kwh;
            $total['r']+=$reading;

            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, $y, $mins);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+1, $y, $fuel);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+2, $y, $kwh);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+3, $y, $reading);
            $xx = $xx+4;
                
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, 1, 'TOTAL');
        $letter_start = PHPExcel_Cell::stringFromColumnIndex($xx);
        $letter_end = PHPExcel_Cell::stringFromColumnIndex($xx+3);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells(''.$letter_start.'1:'.$letter_end.'1');
        $objPHPExcel->getActiveSheet()->getStyle(''.$letter_start.'1:'.$letter_end.'1')->applyFromArray($style);

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, 2, 'RunTime');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+1, 2, 'Fuel ltr');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+2, 2, 'KWH');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+3, 2, 'Reading');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx, $y, number_format($cth,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+1, $y, number_format($ctf,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+2, $y, number_format($ctk,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($xx+3, $y, number_format($ctr,2));

        


    }

    $y++;
    $zzz = 2;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow(1, $y, 'TOTAL');
    foreach(interval($_GET['startdate'],$_GET['enddate'],'Daily') as $g){

        $h = (isset($arr[$g.'h']) ? $arr[$g.'h'] : 0);
        $f = (isset($arr[$g.'f']) ? $arr[$g.'f'] : 0);
        $k = (isset($arr[$g.'k']) ? $arr[$g.'k'] : 0);
        $r = (isset($arr[$g.'r']) ? $arr[$g.'r'] : 0);        
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz, $y, number_format($h,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+1, $y, number_format($f,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+2, $y, number_format($k,2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+3, $y, number_format($r,2));
        $zzz = $zzz + 4;
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz, $y, number_format($total['h'],2));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+1, $y, number_format($total['f'],2));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+2, $y, number_format($total['k'],2));
    $objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($zzz+3, $y, number_format($total['r'],2));
        
}




$objPHPExcel->getActiveSheet()->setTitle('Genset');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="genset.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

