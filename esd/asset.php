<?php
include("config.php");
include("asset_options.php");
session_start();
if(!$_SESSION['esd_username']){
	header("location:login.php");
}
$cond = '';
$f_type='<option value="" selected="selected"> - Type -</option>';
$f_site='<option value="" selected="selected"> - Site -</option>';
$f_location='<option value="" selected="selected"> - Location -</option>';
$f_condition='<option value="" selected="selected"> - Condition -</option>';
$f_status='<option value="" selected="selected"> - Status -</option>';
if(isset($_GET['search'])){
	if(strlen($_POST['type'])>0){
		$cond.=" and assetType='".$_POST['type']."'";
		$f_type='<option value="'.$_POST['type'].'" selected="selected">'.$_POST['type'].'</option>';
	}
	if(strlen($_POST['site'])>0){
		$cond.=" and site='".$_POST['site']."'";
		$f_site='<option value="'.$_POST['site'].'" selected="selected">'.$_POST['site'].'</option>';
	}
	if(strlen($_POST['location'])>0){
		$cond.=" and location='".$_POST['location']."'";
		$f_location='<option value="'.$_POST['location'].'" selected="selected">'.$_POST['location'].'</option>';
	}
	if(strlen($_POST['condition'])>0){
		$cond.=" and condition='".$_POST['condition']."'";
		$f_condition='<option value="'.$_POST['condition'].'" selected="selected">'.$_POST['condition'].'</option>';
	}
	if(strlen($_POST['status'])>0){
		$cond.=" and status='".$_POST['status']."'";
		$f_status='<option value="'.$_POST['status'].'" selected="selected">'.$_POST['status'].'</option>';
	}
}

if(isset($_GET['delete'])){
	if (!in_array($_SESSION['esd_username'], $allowed_users)){
		die('You are not allowed to delete this asset. Please click the BACK button');
	}
	else{
		$delete = sqlsrv_query($conn,"update asset set is_deleted=1 where id='".$_GET['delete']."'");
		header("location: asset.php?deleted=1");
	}
	
}

?>
<!DOCTYPE html>
<html lang="en">
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
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>



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

</head>

<body class="page-header-fixed page-quick-sidebar-over-content page-full-width">
<!-- BEGIN HEADER -->



<?php include("header.php");?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<div class="modal fade bs-modal-lg" id="user_modal" tabindex="-1" role="user_modal" aria-hidden="true" style="height:800px;">
            	<div class="modal-dialog modal-lg" style="height:800px;">
                	<div class="modal-content" style="height:800px;">
                    	<div class="modal-header">
                        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        	<h4 class="modal-title">Asset Folders</h4>
                    	</div>
	                    <div class="modal-body">	                       
	                         <iframe src="" frameborder="0" id="asset_frame" width="100%"  style="height:800px;"></iframe>
	                    </div>                    
                	</div>
            	</div>
        	</div>
        	<div class="row">
				<div class="col-md-12">
					<form action="asset.php?search=on" method="post">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						ESD <small>Asset Management</small>
						</h3>
						
						<ul class="page-breadcrumb breadcrumb">
							<li>							
								<a href="#">Filters:</a>
							</li>
							<li>
								<select class="form-control input-sm" name="type" id="type">
					 				<?php echo $f_type; ?>
					 				<?php
					 					$qtype = sqlsrv_query($conn,"select distinct assetType from asset where (is_deleted is null OR is_deleted=0) order by assetType");
					 					while($type = sqlsrv_fetch_array($qtype)){
					 						echo '<option value="'.$type['assetType'].'">'.$type['assetType'].'</option>';
					 					}
					 				?>
					 			</select>		
							</li>
							<li>
								<select class="form-control input-sm" name="site" id="site">
					 				<?php echo $f_site; ?>
					 				<?php echo $site_options;?>
					 			</select>		
							</li>
							<li>
								<select class="form-control input-sm" name="location" id="location">
					 				<?php echo $f_location; ?>
					 				<?php echo $location_options;?>
					 			</select>		
							</li>
							<li>
								<select class="form-control input-sm" name="condition" id="condition">
					 				<?php echo $f_condition; ?>
					 				<?php echo $condition_options;?>
					 			</select>		
							</li>
							<li>
								<select class="form-control input-sm" name="status" id="status">
					 				<?php echo $f_status; ?>
					 				<?php echo $status_options;?>
					 			</select>		
							</li>				
							<li>
								<input type="submit" class="btn green btn-sm" value="Go">
								<a href="asset.php" class="btn purple btn-sm" style="color:white;">Reset</a>						
							</li>	
						</ul>
					</form>
					
					<!-- END PAGE TITLE & BREADCRUMB-->
				</div>
			</div>
			<div class="clearfix">
			</div>
			<div class="row ">
				<div class="col-md-12 col-sm-12">
					<?php if(isset($_GET['deleted'])) { ?>
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Success!</strong> Asset has been deleted.
						</div>
					<?php } ?>
					<div class="portlet box green-haze">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-bars"></i>Asset List
							</div>
							<div class="actions">
								<div class="btn-group">
									<a class="btn btn-default btn-sm" href="#" data-toggle="dropdown">
									Columns <i class="fa fa-angle-down"></i>
									</a>
									<div id="sample_4_column_toggler" class="dropdown-menu hold-on-click dropdown-checkboxes pull-right">
										<label><input type="checkbox" checked data-column="0">Actions</label>
										<label><input type="checkbox" checked data-column="1">Tag#</label>
										<label><input type="checkbox" checked data-column="2">Asset Type</label>
										<label><input type="checkbox" checked data-column="3">Description</label>
										<label><input type="checkbox" checked data-column="4">Manufacturer</label>
										<label><input type="checkbox" checked data-column="5">Model</label>
										<label><input type="checkbox" checked data-column="6">Serial #</label>
										<label><input type="checkbox" checked data-column="7">Year Manufactured</label>
										<label><input type="checkbox" checked data-column="8">Commissioning Date</label>
										<label><input type="checkbox" checked data-column="9">Site</label>
										<label><input type="checkbox" checked data-column="10">Location</label>
										<label><input type="checkbox" checked data-column="11">Condition</label>
										<label><input type="checkbox" checked data-column="12">Status</label>
										<label><input type="checkbox" checked data-column="13">Vendor</label>
										<label><input type="checkbox" checked data-column="14">P.O. Ref</label>
										<label><input type="checkbox" checked data-column="15">P.O. Value</label>									
									</div>									
								</div>
								<?php if (in_array($_SESSION['esd_username'], $allowed_users)){ ?>
									<a class="btn btn-default btn-sm" href="asset_add.php"><i class="fa fa-plus"></i> Add New</a>
								<?php } ?>
								<a class="btn btn-default btn-sm" href="#" onclick="exportToExcel('#sample_4');"><i class="fa fa-file-excel-o"></i> Export</a>
							</div>														
						</div>
						<div class="portlet-body">	
								<table class="table" id="sample_4">
									<thead>
										<tr>
											<th>Actions</th>
											<th>Tag#</th>
											<th>Asset Type</th>
											<th>Description</th>
											<th>Manufacturer</th>
											<th>Model</th>	
											<th>Serial #</th>
											<th>Year Manufactured</th>
											<th>Commissioning Date</th>
											<th>Site</th>
											<th>Location</th>
											<th>Condition</th>
											<th>Status</th>
											<th>Vendor</th>
											<th>P.O. Ref</th>
											<th>P.O. Value</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$assets = sqlsrv_query($conn,"select *,CONVERT(varchar(23), commissioningDate, 121) as comdate from asset where (is_deleted is null OR is_deleted=0) ".$cond." order by id desc");
											while($r = sqlsrv_fetch_array($assets)){
												$btn = '';
												if (in_array($_SESSION['esd_username'], $allowed_users)){
													$btn = '<a href="asset_edit.php?id='.$r['id'].'" title="Update Asset '.$r['tag'].'"><i class="fa fa-pencil"></i></a>&nbsp;
															<a href="#" onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset.php?delete='.$r['id'].'")} else {return false;}\' title="Delete Asset '.$r['tag'].'"><i class="fa fa-times"></i></a>&nbsp;';
												}
												echo '
													<tr>
														<td align="center">
															'.$btn.'
															<a href="#" onclick=\'show('.$r['id'].');\' title="Asset Categories for '.$r['tag'].'"><i class="fa fa-file-photo-o"></i></a>						
														</td>
														<td>'.$r['tag'].'</td>
														<td>'.$r['assetType'].'</td>
														<td>'.$r['description'].'</td>
														<td>'.$r['manufacturer'].'</td>
														<td>'.$r['model'].'</td>
														<td>'.$r['serial'].'</td>
														<td>'.$r['yearManufactured'].'</td>
														<td>'.$r['comdate'].'</td>
														<td>'.$r['site'].'</td>
														<td>'.$r['location'].'</td>
														<td>'.$r['condition'].'</td>
														<td>'.$r['status'].'</td>
														<td>'.$r['vendor'].'</td>
														<td>'.$r['poReference'].'</td>
														<td align="right">'.number_format($r['poValue'],2).'</td>
													</tr>
												';
											}
										?>
									</tbody>
								</table>						
						
						</div>
					</div>
				</div>				
			</div>
			<div class="clearfix">
			</div>			
		</div>
	</div>
	<!-- END CONTENT -->
	
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
		 <?php echo date('Y');?> &copy; ICT - PMC		 
	</div>
	<div class="page-footer-tools">
		<span class="go-top">
		<i class="fa fa-angle-up"></i>
		</span>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="metronic/assets/global/plugins/respond.min.js"></script>
<script src="metronic/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
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

<script type="text/javascript" src="metronic/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="scripts/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>
<script>
	function exportToExcel(table){
		jQuery(table).table2excel({
			name: "Assets",
			filename: "Assets" //do not include extension
		});
	}
	function show(i){
		$('#asset_frame').attr('src','asset_category.php?id='+i);
        $('#user_modal').modal('show');
	}
</script>


<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   TableAdvanced.init();

});
</script>


<script>
	var TableAdvanced = function () {

	    var initTable4 = function () {
	        var table = $('#sample_4');

	        var oTable = table.dataTable({
	            "columnDefs": [{
	                "orderable": false,
	                "targets": [0]
	            }],
	            "order": [
	                [1, 'asc']
	            ],
	            "lengthMenu": [
	                [5, 15, 20, -1],
	                [5, 15, 20, "All"] // change per page values here
	            ],
	            // set the initial value
	            "pageLength": -1,
	        });

	        var tableWrapper = $('#sample_4_wrapper'); // datatable creates the table wrapper by adding with id {your_table_jd}_wrapper
	        var tableColumnToggler = $('#sample_4_column_toggler');

	        /* modify datatable control inputs */
	        tableWrapper.find('.dataTables_length select').select2(); // initialize select2 dropdown

	        

	        /* handle show/hide columns*/
	        $('input[type="checkbox"]', tableColumnToggler).change(function () {
	            /* Get the DataTables object again - this is not a recreation, just a get of the object */
	            var iCol = parseInt($(this).attr("data-column"));
	            var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
	            oTable.fnSetColumnVis(iCol, (bVis ? false : true));
	        });
	    }
	    return {
	        init: function () {
	            if (!jQuery().dataTable) {
	                return;
	            }
	            initTable4();	            
	        }

	    };

	}();
</script>

</body>
<!-- END BODY -->
</html>