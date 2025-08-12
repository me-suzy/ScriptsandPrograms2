<?php
	// define the arrays
	$iconArray  = array('red.jpg', 'yellow.jpg', 'green.jpg');
	$colorArray = array('red', 'yellow', 'green');

	if (!isset($_SESSION['dataset'])) {
		$q = "select distinct status from " . $_SESSION['prefix'] . "_data";
		$s = mysql_query($q) or die(mysql_error());
		
		$i = 1; $counter = 0;
		while ($r = mysql_fetch_assoc($s))
		{
			$stat = new Status();
			$stat->set('name', $r['status']);
			$stat->set('position', $i);
			if ($counter < count($iconArray)) {
				$stat->set('icon', $iconArray[$counter]);
				$stat->set('color', $colorArray[$counter++]);	
			}
			else {
				$stat->set('icon', '');
				$stat->set('color', '');	
			}
			$stat->commit();
			
			$cmd = "update " . $_SESSION['prefix'] . "_data set status = " . $stat->get('id') . " where status = '" . $r['status'] . "'";
			mysql_query($cmd) or die(mysql_error());
		}
		
		// now take this data and insert it into the table as we created in step 1
		// having it in the database will allow us more esaily manipulate it
		$_SESSION['dataset'] = true;
	}
	
	// this is the code to perform the non page moving functions
	if (isset($_POST['edit']) && !isset($_POST['selNames']))
		$error_msg = "Please Select a Status to Update";
	else if (isset($_POST['selNames'], $_POST['edit'])) {
		$p = new Status($_POST['selNames']);		
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['add']) && !empty($_POST['newName'])) {
		$stat = new Status($_POST['id']);
		$stat->set('name', $_POST['newName'], 'stripslashes');
		$stat->commit();
			
	}
	else if (isset($_POST['newName']) && empty($_POST['newName'])) {
		$stat = new Status($_POST['id']);
		$error_msg = "Please Enter a Status Label";
		$_POST['edit'] = true;		// this is so the text box reappears
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['selNames'], $_POST['up'])) {
		// move status position down
		$stat = new Status($_POST['selNames']);
		$stat->moveDown();
	}
	else if (isset($_POST['up']) && !isset($_POST['selNames'])) {
		// error
		$error_msg = "Please Select a Status to Move";
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['selNames'], $_POST['down'])) {
		// move status position up
		$stat = new Status($_POST['selNames']);
		$stat->moveUp();
	}
	else if (isset($_POST['down']) && !isset($_POST['selNames'])) {
		// error
		$error_msg = "Please Select a Status to Move";
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['iconlink'], $_POST['selNames'], $_POST['selIcons'])) {
		$stat = new Status($_POST['selNames']);
		$stat->set('icon', $_POST['selIcons'], 'stripslashes');
		$stat->commit();
	}
	else if (isset($_POST['iconlink']) && (!isset($_POST['selNames']) || !isset($_POST['selIcons'])) ) {
		$error_msg = "You Must Select a Status to Link With";
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['colorlink'], $_POST['selNames'], $_POST['selColors'])) {
		$stat = new Status($_POST['selNames']);
		$stat->set('color', $_POST['selColors'], 'stripslashes');
		$stat->commit();
	}
	else if (isset($_POST['colorlink']) && (!isset($_POST['selNames']) || !isset($_POST['selColors'])) ) {
		$error_msg = "You Must Select a Status to Link With";
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['move'])) {
		// form cannot be committed without a lack of blanks in the prorities table
		$q = "select id from " . $_SESSION['prefix'] . "_status where name = ''";
		#die(var_dump($q));
		if (mysql_num_rows(mysql_query($q))) {
			$error_msg = "All Priority Labels must not be Empty Strings";	
		}
		else {
			// change the column
			mysql_query("alter table " . $_SESSION['prefix'] . "_data change status status int not null default '1'") or die(mysql_error());
			
			// success - redirect
			unset($_SESSION['dataset']);
			header("Location: category.php");
		}
	}
?>