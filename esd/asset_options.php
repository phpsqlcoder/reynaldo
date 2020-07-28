<?php
$site_options='
<option value="MILL">MILL</option>
<option value="MINE">MINE</option>
<option value="EXPLORATION">EXPLORATION</option>
<option value="DAVAO">DAVAO</option>
<option value="OTHER">OTHER</option>
';

$location_options='
<option value="69 KV SUBSTATION #1">69 KV SUBSTATION #1</option>
<option value="69 KV SUBSTATION #2">69 KV SUBSTATION #2</option>
<option value="ADMIN">ADMIN</option>
<option value="AGSAO SHAFT">AGSAO SHAFT</option>
<option value="ASSAY">ASSAY</option>
<option value="BAGUIO SHAFT">BAGUIO SHAFT</option>
<option value="BMEA">BMEA</option>
<option value="COFFER DAM">COFFER DAM</option>
<option value="COMPRESSOR HOUSE">COMPRESSOR HOUSE</option>
<option value="DAM 1">DAM 1</option>
<option value="DAM 2">DAM 2</option>
<option value="DAM 3">DAM 3</option>
<option value="DAM 4">DAM 4</option>
<option value="DAM 5">DAM 5</option>
<option value="DAVAO OFFICE">DAVAO OFFICE</option>
<option value="DOMINION SUBSTATION">DOMINION SUBSTATION</option>
<option value="E15 SHAFT">E15 SHAFT</option>
<option value="ECS COMPOUND">ECS COMPOUND</option>
<option value="ENVI. SUBSTATION">ENVI. SUBSTATION</option>
<option value="GUEST HOUSE">GUEST HOUSE</option>
<option value="HOSPITAL">HOSPITAL</option>
<option value="JR. STAFFHOUSE">JR. STAFFHOUSE</option>
<option value="LEVEL 8 SHAFT">LEVEL 8 SHAFT</option>
<option value="MCC100">MCC100</option>
<option value="MCC500">MCC500</option>
<option value="MCD">MCD</option>
<option value="MECHANICAL SHOP">MECHANICAL SHOP</option>
<option value="MILL-MINE HAUL ROAD">MILL-MINE HAUL ROAD</option>
<option value="PADIGUSAN">PADIGUSAN</option>
<option value="PHSFI">PHSFI</option>
<option value="PINAYONGAN">PINAYONGAN</option>
<option value="POWER HOUSE">POWER HOUSE</option>
<option value="REPEATER STATION">REPEATER STATION</option>
<option value="SAG MILL AREA">SAG MILL AREA</option>
<option value="SOUTH AGSAO">SOUTH AGSAO</option>
<option value="SR. STAFFHOUSE">SR. STAFFHOUSE</option>
<option value="TINAGO">TINAGO</option>
<option value="WATER DAM">WATER DAM</option>
<option value="WEIGH BRIDGE">WEIGH BRIDGE</option>	
';

$condition_options='
<option value="EXCELLENT">EXCELLENT</option>
<option value="GOOD">GOOD</option>
<option value="POOR">POOR</option>		
';

$status_options='
<option value="OPERATIONAL">OPERATIONAL</option>
<option value="NO OPERATIONAL">NO OPERATIONAL</option>
<option value="STANDBY SPARE">STANDBY SPARE</option>
<option value="FOR REPAIR">FOR REPAIR</option>
<option value="FOR DISPOSAL">FOR DISPOSAL</option>
';

$folder_options = array('NAMEPLATE','FACTORY TEST REPORT','DRAWINGS','MANUALS','PHOTOS');

$file_folder = "files/";

$allowed_users = array('rdtortal','jatano');


function createZip($zip,$dir){
	if (is_dir($dir)){

		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){

	    // If file
				if (is_file($dir.$file)) {
					if($file != '' && $file != '.' && $file != '..'){

						$zip->addFile($dir.$file);
					}
				}else{
	     // If directory
					if(is_dir($dir.$file) ){

						if($file != '' && $file != '.' && $file != '..'){

	       // Add empty directory
							$zip->addEmptyDir($dir.$file);

							$folder = $dir.$file.'/';

	       // Read data of the folder
							createZip($zip,$folder);
						}
					}

				}

			}
			closedir($dh);
		}
	}
}


?>