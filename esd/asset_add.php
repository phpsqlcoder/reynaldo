<?php
include("config.php");
include("asset_options.php");
session_start();
if(!$_SESSION['esd_username']){
	header("location:login.php");
}

if (!in_array($_SESSION['esd_username'], $allowed_users)){
	die('You are not allowed to access this page! Please click the BACK button');
}

if(isset($_GET['act'])){
	$add = sqlsrv_query($conn,"INSERT INTO [asset] ([tag],[assetType],[description],[manufacturer],[model],[serial],[yearManufactured],[commissioningDate],[site],[location],[condition],[status],[vendor],[poReference],[poValue]) VALUES ('".$_POST['tag']."','".$_POST['assetType']."','".$_POST['description']."','".$_POST['manufacturer']."','".$_POST['model']."','".$_POST['serial']."','".$_POST['yearManufactured']."','".$_POST['commissioningDate']."','".$_POST['site']."','".$_POST['location']."','".$_POST['condition']."','".$_POST['status']."','".$_POST['vendor']."','".$_POST['poReference']."','".$_POST['poValue']."')
     ");
	header("location:asset_add.php?success=1");
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
			<!-- BEGIN PAGE HEADER-->
			<form method="get" act="index.php">
				<div class="row">
					<div class="col-md-12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->
						<h3 class="page-title">
						ESD <small>Asset Management</small>
						</h3>

					</div>
				</div>
			</form>
					
			<div class="clearfix">
			</div>
			<div class="row ">
				<div class="col-md-8 col-md-offset-2">
					<?php if(isset($_GET['success'])) { ?>
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Success!</strong> New asset has been added.
						</div>
					<?php } ?>
					<div class="portlet box green-haze">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-plus"></i>New Asset
							</div>
							<div class="actions">								
								<a class="btn btn-default btn-sm" href="asset.php"><i class="fa fa-bars"></i> Asset List</a>							
							</div>																					
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12">
									<form action="asset_add.php?act=add" method="post" class="form-horizontal">
										<div class="form-group">
											<label class="col-md-3 control-label">Tag #</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="tag" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Asset Type</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="assetType" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Description</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="description" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Manufacturer</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="manufacturer" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Model</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="model" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Serial #</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="serial" required="required">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Year Manufactured</label>
											<div class="col-md-9">
												<select class="form-control" name="yearManufactured" required="required">
													<option>- Select -</option>										
													<?php 
														$year = date('Y');
														for($x=1900;$x<=2100;$x++){															
															echo '<option value="'.$x.'">'.$x.'</option>';														
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Commissioning Date</label>
											<div class="col-md-9">
												<input type="date" class="form-control" name="commissioningDate">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Site</label>
											<div class="col-md-9">
												<select class="form-control" name="site" required="required">
													<option>- Select -</option>
													<?php echo $site_options;?>	
												</select>		
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Location</label>
											<div class="col-md-9">
												<select class="form-control" name="location" required="required">
													<option>- Select -</option>
													<?php echo $location_options;?>										
												</select>										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Condition</label>
											<div class="col-md-9">
												<select class="form-control" name="condition" required="required">
													<option>- Select -</option>
													<?php echo $condition_options;?>	
												</select>		
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Status</label>
											<div class="col-md-9">
												<select class="form-control" name="status" required="required">
													<option>- Select -</option>
													<?php echo $status_options;?>	
												</select>		
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">Vendor</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="vendor">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">P.O. Reference</label>
											<div class="col-md-9">
												<input type="text" class="form-control" name="poReference">										
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-3 control-label">P.O. Value</label>
											<div class="col-md-9">
												<input type="number" step="0.01" min="0.00" value="0.00" class="form-control" name="poValue">										
											</div>
										</div>
										<div class="form-actions fluid">
											<div class="col-md-offset-9 col-md-3">
												<button type="submit" class="btn green">Submit</button>
												<a class="btn default" href="asset.php">Cancel</a>
											</div>
										</div>
									</form>	
								</div>
							</div>
										
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
		 2014 &copy; Metronic by keenthemes.
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

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>



<!-- 
<script src="metronic/assets/admin/pages/scripts/tasks.js" type="text/javascript"></script>
	END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
});
</script>



</body>
<!-- END BODY -->
</html>