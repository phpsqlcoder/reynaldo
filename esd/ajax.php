<?php
include("config.php");
if($_GET['act']=="checkinput"){
	$ck=sqlsrv_fetch_array(sqlsrv_query($conn,"select * from downtime where
		unitId='".$_POST['unit']."' and (dateStart<='".$_POST['endd']."' AND dateEnd>='".$_POST['startd']."')"));
	if($ck['id']){
		echo "<div class='alert alert-danger'>
								<strong>Error!</strong> There is already an existing downtime record for these dates.
							</div>";		
	}
	else{
		echo "";
	}	

}

if($_GET['act']=="checkinputgenset"){
	$ck=sqlsrv_fetch_array(sqlsrv_query($conn,"select * from genset_utilization where
		unitId='".$_POST['unit']."' and (dateStart<='".$_POST['endd']."' AND dateEnd>='".$_POST['startd']."')"));
	if($ck['id']){
		echo "<div class='alert alert-danger'>
								<strong>Error!</strong> There is already an existing genset utilization record for these dates.
							</div>";		
	}
	else{
		echo "";
	}	

}
if($_GET['act']=="changefilters"){
	$conditions='';
	if($_POST['s_location']<>''){
		$conditions.=" and location='".$_POST['s_location']."'";
	}
	if($_POST['s_type']<>''){
		$conditions.=" and type='".$_POST['s_type']."'";
	}
	$data='<option value="" selected="selected"> - Select Unit -';
	$uq=sqlsrv_query($conn,"select distinct name from unit where id>0 ".$conditions." order by name");
	while($u=sqlsrv_fetch_array($uq)){		
		$data.='<option value="'.$u['name'].'">'.$u['name'];
	}
	echo '<select class="form-control input-sm" name="s_name" id="s_name">'.$data.'</select>';
}
?>