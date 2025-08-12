<?php
	//Revised on May 18, 2005
	//Revised by Jason Farrell
	//Revision Number 3 CopyRight 2005 Help Desk Reloaded
	
	session_start();
	include_once "../config.php";
	include_once "./includes/functions.php";
	
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS) or die("MySQL Server Could Not be Resolved");
	mysql_select_db(DB_DBNAME) or die("Could Not Select the Database");
	checkKBVisibility();
	
	/*
		For this process we want to seperate our data and then extract it, then if a date has been given
		we can give a date range if the date has been inputted in a readable form - if not, we will do our best to display
		the results, while notifying the user about the formatting error
		
		For ordering it will be based solely on page views
	*/
	
	//begin build the query
	$q  = "select distinct id, descrip, mainDate from " . DB_PREFIX . "data ";
	$cond = array();
	
	//first name
	if (!empty($_POST['fnameVal'])) {
		switch ($_POST['fnameOpt'])
		{
			case 'starts':
				$cond[] = "FirstName LIKE '" . mysql_real_escape_string($_POST['fnameVal']) . "%'";
				break;
			case 'contains':
				$cond[] = "FirstName LIKE '%" . mysql_real_escape_string($_POST['fnameVal']) . "%'";
				break;
			case 'ends':
				$cond[] = "FirstName LIKE '%" . mysql_real_escape_string($_POST['fnameVal']) . "'";
				break;
			case 'is':
				$cond[] = "FirstName LIKE '" . mysql_real_escape_string($_POST['fnameVal']) . "'";
				break;
		}
	}
	
	//last name
	if (!empty($_POST['lnameVal'])) {
		switch ($_POST['lnameOpt'])
		{
			case 'starts':
				$cond[] = "LastName LIKE '" . mysql_real_escape_string($_POST['lnameVal']) . "%'";
				break;
			case 'contains':
				$cond[] = "LastName LIKE '%" . mysql_real_escape_string($_POST['lnameVal']) . "%'";
				break;
			case 'ends':
				$cond[] = "LastName LIKE '%" . mysql_real_escape_string($_POST['lnameVal']) . "'";
				break;
			case 'is':
				$cond[] = "LastName LIKE '" . mysql_real_escape_string($_POST['lnameVal']) . "'";
				break;
		}
	}
	
	//email
	if (!empty($_POST['emailVal'])) {
		switch ($_POST['emailOpt'])
		{
			case 'starts':
				$cond[] = "EMail LIKE '" . mysql_real_escape_string($_POST['emailVal']) . "%'";
				break;
			case 'contains':
				$cond[] = "EMail LIKE '%" . mysql_real_escape_string($_POST['emailVal']) . "%'";
				break;
			case 'ends':
				$cond[] = "EMail LIKE '%" . mysql_real_escape_string($_POST['emailVal']) . "'";
				break;
			case 'is':
				$cond[] = "EMail LIKE '" . mysql_real_escape_string($_POST['emailVal']) . "'";
				break;
		}
	}
	
	//PC Catagory
	if (!empty($_POST['pccatVal'])) {
		switch ($_POST['pccatOpt'])
		{
			case 'starts':
				$cond[] = "PCatagory LIKE '" . mysql_real_escape_string($_POST['pccatVal']) . "%'";
				break;
			case 'contains':
				$cond[] = "PCatagory LIKE '%" . mysql_real_escape_string($_POST['pccatVal']) . "%'";
				break;
			case 'ends':
				$cond[] = "PCatagory LIKE '%" . mysql_real_escape_string($_POST['pccatVal']) . "'";
				break;
			case 'is':
				$cond[] = "PCatagory LIKE '" . mysql_real_escape_string($_POST['pccatVal']) . "'";
				break;
		}
	}
	
	//priority
	if (!empty($_POST['priority'])) {
		$cond[] = "priority = '" . mysql_real_escape_string($_POST['priority']) . "'";
	}
	
	//status
	if (!empty($_POST['statusVal'])) {
		$cond[] = "status = '" . mysql_real_escape_string($_POST['statusVal']) . "'";
	}
	
	//pageview
	if (!empty($_POST['pageViewVal'])) {
		$cond[] = "pageView >= " . intval($_POST['pageViewVal']);	
	}
	
	//check only for published tickets
	if (!isset($_SESSION['loggedIn'])) $cond[] = "ticketVisi = 1";
	
	//finish build the query
	if (count($cond)) $q .= "where (" . implode(' AND ', $cond) . ") ";;
	$q .= "order by pageView desc";
	
	$s = mysql_query($q) or die("Query Failed - " . mysql_error());
	while ($r = mysql_fetch_assoc($s))
	{
		$_retArray[$r['id']]['id'] = $r['id'];
		$_retArray[$r['id']]['description'] = generateBlob($r['descrip']);
		$_retArray[$r['id']]['date'] = strtotime($r['mainDate'], time());
		
		$q = "select solution from " . DB_PREFIX . "resolution where id=" . $r['id'];
		$set = mysql_query($q) or die("Query on Resolution Table Failed");
		$_tArray = array();
		while ($row = mysql_fetch_assoc($set))
			$_tArray[] = generateBlob($row['solution']);
			
		//attach the array to the inside of _retArray
		$_retArray[$r['id']]['resolutions'] = $_tArray;
	}
	
	if (!empty($_POST['date1']) && !empty($_POST['date2'])) {
		$lbound = strtotime($_POST['date1'], time());
		$ubound = strtotime($_POST['date2'], time());
		
		if ($lbound > $ubound || $lbound == -1 || $ubound == -1) {
			//strtotime parse error - thus we cannot exclude based on time
			$_SESSION['error_msg'] = "Given Dates were not in parseable format - please enter a different format. Ex: 23:59 Mar 10 2005";
		}
		else {
			//time was successfully parsed - go through the array at the top level and remove entires that do not match
			foreach ($_retArray as $k => $v)
			{
				if ($v['date'] < $lbound || $v['date'] > $ubound) unset($_retArray[$k]);		//this will remove the key	
			}	
		}
	}
	
	$_SESSION['infoArray'] = $_retArray;
	header("Location: getResults.php");
?>