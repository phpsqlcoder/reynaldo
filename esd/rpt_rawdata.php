<?php
include("config.php");
session_start();

if(!isset($_GET['isexcel'])){
?>
<html lang="en">

<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Vehicle | Monitoring</title>
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
			<div class="row">
				<div class="col-md-12"><br><br>
					<table width="100%" id="table1" style="font-family:arial;font-size:12px;">
						<thead>
							<tr align="left">
								<th>Seq</th>
								<th>Unit ID</th>
								<th>Name</th>
								<th>Location</th>								
								<th>Type</th>
								<td>DateStart</td>
								<td>DateStart(time)</td>
								<td>DateEnd</td>
								<td>DateEnd(time)</td>
								<td>Remarks</td>
								<td>AddedBy</td>
								<td>AddedDate</td>
								<td>isScheduled</td>
																	
							</tr>
						</thead>
						<tbody>

					<?php 
						$ldata='';
						$seq=0;					
						$ctr1s=0;
						$lq=sqlsrv_query($conn,"select u.id,u.name,u.type,u.location,
CONVERT(VARCHAR(19),d.dateStart) as dateStart,CONVERT(VARCHAR(19),d.dateEnd) as dateEnd,
CONVERT(VARCHAR(19),d.addedDate) as addedDate,
							d.remarks,d.addedBy,
case d.isscheduled 
	when 0 then 'Breakdown'
	when 1 then 'Planned'
	when 2 then 'Grid Outage'
END as scheduled
 From downtime d left join unit u on u.id=d.unitId");
						while($l=sqlsrv_fetch_array($lq)){							
							$seq++;
							$ctr1s++;
  							if ($ctr1s==2){$bgclr1s='#ffffff';$ctr1s=0;} else { $bgclr1s='#F6F7F6';}
							echo '
								<tr style="background-color:'.$bgclr1s.'">
									<td>'.$seq.'</td>
									<td>'.$l['id'].'</td>
									<td>'.$l['name'].'</td>
									<td>'.$l['location'].'</td>
									<td>'.$l['type'].'</td>								
									<td>'.date('Y-m-d',strtotime($l['dateStart'])).'</td>
									<td>'.date('H:i:s',strtotime($l['dateStart'])).'</td>
									<td>'.date('Y-m-d',strtotime($l['dateEnd'])).'</td>
									<td>'.date('H:i:s',strtotime($l['dateEnd'])).'</td>
								
									<td>'.$l['remarks'].'</td>
									<td>'.$l['addedBy'].'</td>
									<td>'.$l['addedDate'].'</td>
									<td>'.$l['scheduled'].'</td>
									
								</tr>
							';
						}
					?>
					</tbody>
					</table>
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


<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/index.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/components-pickers.js"></script>
<script src="scripts/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>
<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
exportToExcel('#table1');

});
</script>


<script>
	function exportToExcel(table){
		jQuery(table).table2excel({
			name: "Worksheet Name",
		    filename: "ESD_RAW_DATA" //do not include extension
		}); 
	}
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php } 
?>