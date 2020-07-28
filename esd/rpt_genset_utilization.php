<?php
include("config.php");
session_start();
$st=date('Y-m-d');
$et=date('Y-m-d');
$pla=' selected="selected"';
$ucntr=0;
$tdata='';
$thdata='';

function columnLetter($c){

    $c = intval($c);
    if ($c <= 0) { return ''; }
    else{
    	$letter = '';
             
	    while($c != 0){
	       $p = ($c - 1) % 26;
	       $c = intval(($c - $p) / 26);
	       $letter = chr(65 + $p) . $letter;
	    }
	    
	    return $letter;
    }
    
        
}

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
	$uq=sqlsrv_query($conn,"select * from unit where id in (9,10,11,16,17,18,24,25,26) order by name");
	while($u=sqlsrv_fetch_array($uq)){
		$ucntr++;
		if($ucntr==1){
			$thdata.='<tr align="center"><td>&nbsp;</td><td>&nbsp;</td>';
		}	
		
		$tdata.='<tr>
					<th>'.$ucntr.'</th>
					<th align="center" class="headcol text-center">'.$u['name'].'
					</th>
				';
			$cth = 0;
			$ctf = 0;
			$ctk = 0;
			$ctr = 0;
			
			foreach(interval($_GET['startdate'],$_GET['enddate'],'Daily') as $x){								
					
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

				if($ucntr==1){
					$thdata.='
							<td>RunTime</td>
							<td>Fuel ltr</td>
							<td>KWH</td>
							<td>Reading</td>
							';
				}
				$tdata.='
					<td align="right">'.$mins.'</td>
					<td align="right">'.$fuel.'</td>
					<td align="right">'.$kwh.'</td>
					<td align="right">'.$reading.'</td>
					';
			}
		$tdata.='
				<td align="center" style="font-weight:bold;">'.number_format($cth,2).'</td>
				<td align="center" style="font-weight:bold;">'.number_format($ctf,2).'</td>
				<td align="center" style="font-weight:bold;">'.number_format($ctk,2).'</td>
				<td align="center" style="font-weight:bold;">'.number_format($ctr,2).'</td>
				';
		$tdata.='</tr>';
		if($ucntr==1){	
					$thdata.='
						<td style="font-weight:bold;">RunTime</td>
						<td style="font-weight:bold;">Fuel ltr</td>
						<td style="font-weight:bold;">KWH</td>
						<td style="font-weight:bold;">Reading</td>
						<tr>'; 
					}
	}
}
?>
<html lang="en">
	<!--<![endif]-->
	<!-- BEGIN HEAD -->
	<head>
		<meta charset="utf-8"/>
		<title>ESD | Monitoring</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
		<meta content="" name="description"/>
		<meta content="" name="author"/>
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="google.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
		<!-- END GLOBAL MANDATORY STYLES -->
		<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->
		<link href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/clockface/css/clockface.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
		<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
		<!-- END PAGE LEVEL PLUGIN STYLES -->
		<!-- BEGIN PAGE STYLES -->
		<link href="metronic/assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
		<!-- END PAGE STYLES -->
		<!-- BEGIN THEME STYLES -->
		<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
		<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
		<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
		<!-- END THEME STYLES -->
		<link rel="shortcut icon" href="favicon.ico"/>
		<style>
			.popover-title {
		color: black;
		
			}
			.popover-content {
			color: black;
			
			}
		</style>
	</head>
	<body class="page-header-fixed page-full-width">
		<div class="page-container">
			<!-- BEGIN CONTENT -->
			<div class="page-content-wrapper">
				<div class="page-content">
					<form method="get">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label col-md-3">Start</label>
											<div class="col-md-9">
												<div class="input-group date date-picker margin-bottom-5 col-md-12" data-date-format="yyyy-mm-dd">
													<input type="text" class="form-control form-filter input-sm" readonly name="startdate" id="startdate" value="<?php echo $st;?>">
													<span class="input-group-btn">
														<button class="btn  default" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="control-label col-md-3">End</label>
											<div class="col-md-9">
												<div class="input-group date date-picker margin-bottom-5 col-md-12" data-date-format="yyyy-mm-dd">
													<input type="text" class="form-control form-filter input-sm" readonly name="enddate" id="enddate" value="<?php echo $et;?>">
													<span class="input-group-btn">
														<button class="btn  default" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<table width="100%">
											<tr>
												
												<td><input type="submit" class="btn purple" value="Generate"></td>
											</tr>
										<tr></tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</form>
				<?php
				$header='<tr><th align="center">#</th><th style="text-align:center;">Unit</th>';
				$header2='<tr><th align="center">#</th><th style="text-align:center;">Unit</th>';
				$footer='<tr><td>&nbsp;</td><td style="text-align:center;font-weight:bold;">TOTAL</d>';
				foreach (interval($_GET['startdate'],$_GET['enddate'],"Daily") as $a){
					$header.='<td>'.date('M d',strtotime($a)).'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
					$header2.='<td align="center" colspan="4">'.date('M d',strtotime($a)).'</td>';
					$h = (isset($arr[$a.'h']) ? $arr[$a.'h'] : 0);
					$f = (isset($arr[$a.'f']) ? $arr[$a.'f'] : 0);
					$k = (isset($arr[$a.'k']) ? $arr[$a.'k'] : 0);
					$r = (isset($arr[$a.'r']) ? $arr[$a.'r'] : 0);
					$footer.='
								<td style="font-weight:bold;">'.number_format($h,2).'</td>
								<td style="font-weight:bold;">'.number_format($f,2).'</td>
								<td style="font-weight:bold;">'.number_format($k,2).'</td>
								<td style="font-weight:bold;">'.number_format($r,2).'</td>
							';
				}
				$header.='<td>TOTAL</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
				$header2.='<td align="center" colspan="4" style="font-weight:bold;">TOTAL</td></tr>';
				$footer.='
					<td style="font-weight:bold;">'.number_format($total['h'],2).'</td>
					<td style="font-weight:bold;">'.number_format($total['f'],2).'</td>
					<td style="font-weight:bold;">'.number_format($total['k'],2).'</td>
					<td style="font-weight:bold;">'.number_format($total['r'],2).'</td>
				</tr>';
			?>
			<div class="row">
				<div class="col-md-12">
					<a href="rpt_genset_utilization_excel.php?startdate=<?php echo $_GET['startdate'];?>&enddate=<?php echo $_GET['enddate'];?>" class="btn green btn-sm">Export to Excel</a><br><br>					
					<table width="100%" border="1" id="tableexcel" style="font-style:Arial;font-size:14px;background-color:white;" class="table-striped table-condensed">
						<?php echo $header2; ?>
						<?php echo $thdata;?>
						<?php echo $tdata;?>
						<?php echo $footer; ?>						
					</table>
					
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</body>
<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="metronic/assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="metronic/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="metronic/assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.js" type="text/javascript"></script>
<script src="metronic/assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
<script src="scripts/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>
<script>
	function exportToExcel(table){
		jQuery(table).table2excel({
			name: "Genset Utilization",
		filename: "genset_utilization" //do not include extension
		});
	}
</script>
<script>
jQuery(document).ready(function() {
Metronic.init(); // init metronic core components
Layout.init(); //
ComponentsPickers.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>