<?php
	//resolve the proper column name
	$q = "show columns from " . $_SESSION['prefix'] . "_data";
	$s = mysql_query($q) or die(mysql_error());
	while ( $r = mysql_fetch_assoc( $s ) )
		$columns[] = $r['Field'];	
		
	if (in_array('PCatagory', $columns)) {
		$name = 'PCatagory';
		$nameChange = true;	
	}
	else {
		$name = 'category';
		$nameChange = false;	
	}

	if (!isset($_SESSION['dataset'])) {
		// determine the random priority for each catagory
		$s = mysql_query("select count(pid) as num from " . $_SESSION['prefix'] . "_priorities") or die(mysql_error());
		$num_p = mysql_result($s, 0);
		
		$q = "select distinct $name from " . $_SESSION['prefix'] . "_data";
		$s = mysql_query($q) or die(mysql_error());
		
		while ($r = mysql_fetch_assoc($s))
		{
			$stat = new Category();
			$stat->set('name', $r[$name]);
			$stat->set('priority', new Priority(( (rand() % $num_p) + 1)));
			$stat->commit();
			
			$cmd = "update " . $_SESSION['prefix'] . "_data set $name = " . $stat->get('id') . " where $name = '" . $r[$name] . "'";
			mysql_query($cmd) or die(mysql_error());
		}
		
		// now take this data and insert it into the table as we created in step 1
		// having it in the database will allow us more esaily manipulate it
		$_SESSION['dataset'] = true;
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_POST['link'], $_POST['selNames'], $_POST['selP'])) {
		$c = new Category( $_POST['selNames'] );
		$c->set('priority', new Priority($_POST['selP']));
		$c->commit();
	}
	elseif (isset($_POST['link']) && ( !isset($_POST['selNames']) || !isset($_POST['selP']) ) ) {
		$error_msg = "You Must Select a Category and Priority to Link";
	}
	
	if (isset($_POST['move'])) {
		// form cannot be committed without a lack of blanks in the prorities table
		#die(var_dump($q));
		// change the column
		if ($nameChange)
			mysql_query("alter table " . $_SESSION['prefix'] . "_data change PCatagory category int not null default '1'") or die(mysql_error());
		else
			mysql_query("alter table " . $_SESSION['prefix'] . "_data change category category int not null default '1'") or die(mysql_error());
		
		// success - redirect
		unset($_SESSION['dataset']);
		header("Location: user.php");
	}
?>