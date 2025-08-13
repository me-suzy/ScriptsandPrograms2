<?
if(!isset($fileworks)) {
 	$fileworks = "";
}

switch($fileworks) {
	
	default:
	if($input_method == "5") {
		$filename = $HTTP_GET_VARS["filename"];
		$filereturn = $HTTP_GET_VARS["filereturn"];
	}
	break;

	case "init":
		$filename = $HTTP_GET_VARS["filename"];
		$filereturn = $HTTP_GET_VARS["filereturn"];


		// following functionality will be added later
		// check CHMOD of the file and directory if it will be backward writtable
		//create backup copy of the file
		
		
			// two levels deep path has its reason because this file is in ./_i3/inc/
			// directory but accesses file directly to generate output for IFRAME!
			if($fp = fopen("../../".$filename,"r")) {

				$filecontent = "";
				while(!feof($fp)) {
					$filecontent .= fgets($fp,4096);
				}
			
				fclose($fp);
				echo stripslashes($filecontent);
			
			} else {
				echo "<script language=\"Javascript\">alert('Error: Cannot open file ".$filename.".');</script>";
			}

	break;

case "save":
	// input is following $dbhost,$dbuser,$dbpass,$dbname,$dbtable,$dbfield,$dbrecord,$dbsafe,$dbreturn,$edited

		$edited = stripslashes($HTTP_POST_VARS["EditorValue"]);
		$filename = $HTTP_POST_VARS["filename"];
		$filereturn = $HTTP_POST_VARS["filereturn"];
		
			if($fp = fopen($filename,"w+")) {

				if(fwrite($fp,$edited)) {
				
					echo "<script language=\"Javascript\">window.location = '".$filereturn."';</script>";

				} else { 
					echo "<script language=\"Javascript\">alert('Error: Cannot write to file. check permissions, file must have CHMOD 0777');</script>";
				}


			fclose($fp);
			} else {
				echo "<script language=\"Javascript\">alert('Error: Cannot open file ".$filename.".');</script>";
			}
break;
}
?>