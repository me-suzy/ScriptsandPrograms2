<?php
	if ($OBJ->get('enable_file_blocking')) {
		if (isset($_POST['cmdAddExt'])) {
			if (!empty($_POST['extAdd'])) $OBJ->addExt(mysql_real_escape_string($_POST['extAdd']));
		}
		elseif (isset($_POST['cmdAddName'])) {
			if (!empty($_POST['fname'])) $OBJ->addName(mysql_real_escape_string($_POST['fname']), $_POST['pos']);
		}
		elseif (isset($_POST['cmdDelName'])) {
			if (!empty($_POST['name'])) $OBJ->delName(intval($_POST['name']));
		}
		elseif (isset($_POST['cmdDelExt'])) {
			if (!empty($_POST['ext'])) $OBJ->delExt(mysql_real_escape_string($_POST['ext']));
		}
	}
	
	if (isset($_POST['submit'])) {
		//this can update the size limit and whether to enable file blocking
		$OBJ->set('enable_file_blocking', (isset($_POST['enableBlock'])) ? true : false);
		if ($OBJ->setSize($_POST['size'])) {
	#		print "<pre>";
	#		var_dump($OBJ);
	#		print "</pre>";
	#		exit;
			
			$OBJ->commit();
			$error_msg = "Changes Successful";
		}
		else {
			$error_msg = "Changes Failed - File Size Exceeds Allowable Limit";	
		}
	}
	
	$_SESSION['obj'] = serialize($OBJ);
	#die(var_dump($_SESSION['obj']));
?>