<?php
include("config.php");
include("asset_options.php");
session_start();
if(!$_SESSION['esd_username']){
	header("location:login.php");
}
if(!isset($_GET['id'])){die('Invalid Request!');}

if(isset($_GET['downloadall'])){
	
		$zip = new ZipArchive();
		$filename = "./ESD_files.zip";

		if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("cannot open <$filename>\n");
		}

		$dir = $file_folder.$_GET['id'].'/';

		// Create zip
		createZip($zip,$dir);

		$zip->close();

		$filename = "ESD_files.zip";

		if (file_exists($filename)) {
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename="'.basename($filename).'"');
		header('Content-Length: ' . filesize($filename));

		flush();
		readfile($filename);
		// delete file
		unlink($filename);

		}


}

if(isset($_GET['delete'])){

	if (!in_array($_SESSION['esd_username'], $allowed_users)){
		die('You are not allowed to delete this media. Please click the close button');
	}
	else{
		unlink($_GET['delete']);
	}
}
if(isset($_GET['addnew'])){
	for($i=0; $i<count($_FILES['nfile']['name']); $i++) {
		$target_file = $file_folder.$_GET['id']."/".$_POST['type']."/".basename($_FILES["nfile"]["name"][$i]);
		if (move_uploaded_file($_FILES["nfile"]["tmp_name"][$i], $target_file)) {
	        //echo "The file ". basename( $_FILES["nfile"]["name"][$i]). " has been uploaded.";
	    } else {
	        echo '<strong style="color:white;">Sorry, there was an error uploading your file. '.basename($_FILES["nfile"]["name"][$i]).'</strong><br>';
	    }
	}
	
}
$header = '<ul class="mix-filter">
				<li class="filter" data-filter="all">
					 ALL
				</li>
			';
$all = '';
$x = 0;
foreach($folder_options as $f){
	$x++;
	$header.='
		<li class="filter" data-filter="category_'.$x.'">
			'.$f.'
		</li>
	';
	//Create folder if not exist
	$url = $file_folder.$_GET['id']."/".$f;
	if (!file_exists($url)) {
	    mkdir($url, 0777, true);
	}

	//read content of each folder
	$files = array_diff(scandir($url), array('.', '..'));
	foreach($files as $file){		
		$converted = convert($_GET['id'],$file,$x,$url."/".$file,$allowed_users);
		$all.=$converted;
		//echo $file."<br>";
		
	}

}
$header.='</ul>';



function convert($id,$file,$cat,$url,$allowed_users){
	$ext = strtolower(getExt($file));
	$e_excel = array('xls','xlsx','csv');
	$e_word = array('doc','docx');
	$e_pdf = array('pdf');
	$e_audio = array('mp3','wav','wma','m4a');
	$e_video = array('avi','flv','wmv','mov','mp4');
	$e_image = array('jpeg','jpg','tif','png','gif');
	if(in_array($ext,$e_excel)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/excel.png" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>	
						'.$btn.'
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>				
			</div>
		';
	}
	elseif(in_array($ext,$e_word)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/word.jpg" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>	
						'.$btn.'			
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button"><i class="fa fa-search"></i></a>
					</div>
					'.$file.'
				</div>				
			</div>
		';
	}
	elseif(in_array($ext,$e_pdf)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/pdf.jpg" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>	
						'.$btn.'					
						<a class="mix-preview fancybox-button" href="'.$url.'" title="download file" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>				
			</div>
		';
	}
	elseif(in_array($ext,$e_audio)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/mp3.jpg" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>	
						'.$btn.'						
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>				
			</div>
		';
	}
	elseif(in_array($ext,$e_video)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/movie.png" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>		
						'.$btn.'					
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>				
			</div>
		';
	}
	elseif(in_array($ext,$e_image)){
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="'.$url.'" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>			
						'.$btn.'		
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>
			</div>
		';
	}
	else{
		$btn = '';
		if (in_array($_SESSION['esd_username'], $allowed_users)){
			$btn = '<a class="mix-link" href="#" title="delete this file"  onclick=\'if (confirm("Are you sure you want to delete this asset?") == true) {window.location.replace("asset_category.php?delete='.$url.'&id='.$id.'")} else {return false;}\'><i class="fa fa-times"></i></a>';
		}
		$converted = '
			<div class="col-md-3 col-sm-3 mix category_'.$cat.'">
				<div class="mix-inner">
					<img class="img-responsive" style="width:100px;height:100px;" src="preview_images/file.jpg" alt="">
					<div class="mix-details">
						<h4>'.substr($file,0,12).'</h4>		
						'.$btn.'	
						<a class="mix-preview fancybox-button" href="'.$url.'" target="_blank" data-rel="fancybox-button">
						<i class="fa fa-search"></i>
						</a>
					</div>
					'.$file.'
				</div>
				
			</div>
		';
	}

	return $converted;
}

function getExt($path){
	return pathinfo($path, PATHINFO_EXTENSION);
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
<link href="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/pages/css/portfolio.css" rel="stylesheet" type="text/css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="metronic/assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="metronic/assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="metronic/assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>



<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>

</head>

<body class="page-full-width">

<div class="page-container">
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			
					
			<div class="clearfix">
			</div>
			<div class="row ">
				<div class="col-md-12">
					<?php if(isset($_GET['success'])) { ?>
						<div class="alert alert-warning alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
							<strong>Success!</strong> New file has been added.
						</div>
					<?php } ?>
					<div class="portlet box green-haze">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-file-picture-o"></i>
							</div>	
							<div class="actions" style="position:relative;top:-5px;">
								<form class="form-horizontal" action="asset_category.php?addnew=1&id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data">
									<table border="0">
										<tr>
											<td>Upload File:</td>											
											<td>
												<select name="type" id="type" class="form-control input-sm" required="required" style="height:25px;">
													<option value="">-Select Folder-</option>
													<?php
														foreach($folder_options as $fo){
															echo '<option value="'.$fo.'">'.$fo.'</option>';
														}
													?>
												</select>
											</td>
											<td align="right"><input type="file" name="nfile[]" required="required" multiple="multiple" ></td>
											<td><input type="submit" class="btn red btn-sm" value="Upload"></td>
										</tr>
									</table>
								</form>
							</div>																										
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-12">
									<?php echo $header;?>
									<div class="row mix-grid">
										<?php echo $all;?>
									</div>
								</div>
							</div>	
							<div class="row">
								<div class="col-md-12">
									<a href="asset_category.php?downloadall=<?php echo $_GET['id']?>&id=<?php echo $_GET['id']?>" class="pull-right">Download All</a>
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
<script type="text/javascript" src="metronic/assets/global/plugins/jquery-mixitup/jquery.mixitup.min.js"></script>
<script type="text/javascript" src="metronic/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script src="metronic/assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="metronic/assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="metronic/assets/admin/pages/scripts/portfolio.js"></script>
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init() // init quick sidebar
   Portfolio.init();
});

</script>



</body>
<!-- END BODY -->
</html>