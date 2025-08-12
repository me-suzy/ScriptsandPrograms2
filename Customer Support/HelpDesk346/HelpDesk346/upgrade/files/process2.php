<?php
	if (!isset($_SESSION['dataset'])) {
		$q = "select distinct priority from " . $_SESSION['prefix'] . "_data";
		$s = mysql_query($q) or die(mysql_error());
		
		$i = 1;
		while ($r = mysql_fetch_assoc($s))
		{
			$p = new Priority();
			$p->set('name', $r['priority']);
			$p->set('severity', $i);
			$p->commit();
			
			$cmd = "update " . $_SESSION['prefix'] . "_data set priority = " . $p->get('pid') . " where priority = '" . $r['priority'] . "'";
			mysql_query($cmd) or die(mysql_error());
		}
		
		// now take this data and insert it into the table as we created in step 1
		// having it in the database will allow us more esaily manipulate it
		$_SESSION['dataset'] = true;
	}
	
	// this is the code to perform the non page moving functions
	if (isset($_POST['edit']) && !isset($_POST['selNames']))
		$error_msg = "Please Select a Priority Label to Update";
	else if (isset($_POST['selNames'], $_POST['edit'])) {
		$p = new Priority($_POST['selNames']);		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['add']) && !empty($_POST['newName'])) {
		$p = new Priority($_POST['pid']);
		$p->set('name', $_POST['newName'], 'stripslashes');
		$p->commit();
			
	}
	else if (isset($_POST['newName']) && empty($_POST['newName'])) {
		$p = new Priority($_POST['pid']);
		$error_msg = "Please Enter a Priority Label";
		$_POST['edit'] = true;		// this is so the text box reappears
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['selNames'], $_POST['up'])) {
		// move priority position up
		$p = new Priority($_POST['selNames']);
		$p->DecreaseSeverity();
	}
	else if (isset($_POST['up']) && !isset($_POST['selNames'])) {
		// error
		$error_msg = "Please Select a Priority to Move";
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['selNames'], $_POST['down'])) {
		// move priority position up
		$p = new Priority($_POST['selNames']);
		$p->IncreaseServerity();
	}
	else if (isset($_POST['down']) && !isset($_POST['selNames'])) {
		// error
		$error_msg = "Please Select a Priority to Move";
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['move'])) {
		// form cannot be committed without a lack of blanks in the prorities table
		$q = "select pid from " . $_SESSION['prefix'] . "_priorities where priority = ''";
		#die(var_dump($q));
		if (mysql_num_rows(mysql_query($q))) {
			$error_msg = "All Priority Labels must not be Empty Strings";	
		}
		else {
			// change the column
			mysql_query("alter table " . $_SESSION['prefix'] . "_data change priority priority int not null default '1'") or die(mysql_error());
			
			// success - redirect
			unset($_SESSION['dataset']);
			header("Location: status.php");
		}
	}
?>