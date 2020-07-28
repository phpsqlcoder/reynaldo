<?php
	$serverName = "172.16.20.42\AGUSAN_DB";
	$connectionInfo = array( "Database"=>"PMC-AGUSAN-NEW", "UID"=>"sa", "PWD"=>"@Temp123!", "CharacterSet" => "UTF-8" );

	$conn = sqlsrv_connect( $serverName, $connectionInfo);
	date_default_timezone_set('Asia/Manila');
$latest = sqlsrv_fetch_array(sqlsrv_query($conn,"select top 1 CONVERT(VARCHAR(19),DTRDate) as DTRDated from HRDTRH order by dtrdate desc"));
if(isset($_GET['start'])) {

	$data = '';
	$header = '<tr>
				<td>Employee ID</td>
				<td>Department</td>
				<td>Name</td>
				<td>Position</td>
				';
	$start = strtotime($_GET['start']);
	$end = strtotime($_GET['end']);
	for ( $a = $start; $a <= $end; $a += 86400 ){
		$date = date('Y-m-d',$a);	
	  	$header.='<td>'.$date.'</td>';
	}
	$header .= '</tr>';
	$q = sqlsrv_query($conn,"select * from jundrie_empReports where divisionname='ENGINEERING & CONSTRUCTION SERVICES DIVISION' and active=1");
	while($r = sqlsrv_fetch_array($q)){

		
		$data.='<tr>
					<td>'.$r['EmpID'].'</td>
					<td>'.$r['DeptDesc'].'</td>
					<td>'.$r['FullName'].'</td>
					<td>'.$r['PositionDesc'].'</td>';
		
		for ( $a = $start; $a <= $end; $a += 86400 ){
			$date = date('Y-m-d',$a);
			$time = sqlsrv_fetch_array(sqlsrv_query($conn,"select * from hrdtrh where dtrdate='".$date."' and empid='".$r['EmpID']."'"));
	      	$data.='<td align="right">'.number_format($time['RegHrs'],2).'</td>';
		}
	}
}
?>
<form action="dtr.php" method="get">
<table style="font-family:Arial;font-size:12px;">
	<tr><td align="center" colspan="3"><h2>Daily Time Record</h2></td></tr>
	<tr>
		<td>Start Date: <input type="date" name="start" <?php if(isset($_GET['start'])) echo 'value="'.$_GET['start'].'"'?>></td>
		<td>End Date: <input type="date" name="end" max="<?php echo date('Y-m-d',strtotime($latest['DTRDated'])); ?>" <?php if(isset($_GET['end'])) echo 'value="'.$_GET['end'].'"'?>> </td>
		<td><input type="submit" value="Generate"></td>
	</tr>
	<tr><td colspan="3">Latest date: <?php echo date('F d, Y',strtotime($latest['DTRDated'])); ?></td></tr>
</table>
</form>
<?php if(isset($_GET['start'])) { ?>
	<table border="0" id="table1">
		<?php echo $header;?>
		<?php echo $data;?>
	</table>
	<script src="metronic/assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
	<script src="metronic/assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
	<script src="scripts/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>
	<script>
		function exportToExcel(table){
			jQuery(table).table2excel({
				name: "DTR",
				filename: "DTR" //do not include extension
			});
		}
		jQuery(document).ready(function() {    
		  exportToExcel('#table1');

		});
		
	</script>


<?php } ?>
