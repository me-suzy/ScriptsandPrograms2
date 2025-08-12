<?php
	//processing segment
	//data sets
	
	$t->set('descrip', $_POST['descrip'], 'mysql_real_escape_string');;
	$t->set('status', new Status($_POST['status']));
	if (isset($_POST['priority'])) $t->set('priority', new Priority($_POST['priority']));
	
	$t->commit();		//store the new data
	
	if (isset($_FILES['file']) && strlen($_FILES['file']['name'])) {
		if ($OBJ->CheckFile($_FILES['file']['name']) && $OBJ->checkSize($_FILES['file']['size'])) {
			//process the file being uploaded
			if (!file_exists("./uploaded_files/")) mkdir("uploaded_files");
			if (!move_uploaded_file($_FILES['file']['tmp_name'], "./uploaded_files/" . $tid . "_" . $_FILES['file']['name']))
				die("Upload Failed");
			else {
				$q = "select id from " . DB_PREFIX . "files where id = $tid and name = '" . $_FILES['file']['name'] . "'";
				if (!mysql_num_rows(mysql_query($q))) {
					$cmd = "insert into " . DB_PREFIX . "files(id, name) values(" . intval($tid) . ", '" . $_FILES['file']['name'] . "')";
					mysql_query($cmd) or die(mysql_error());
				}
			}
		}
		else {
			$error_msg = "File is Not Valid for Upload - Blocking may be enabled or Max Size Exceeded";
		}
	}
?>