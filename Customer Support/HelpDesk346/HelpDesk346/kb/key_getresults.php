<?php
	//Revised on May 21 2005
	//Revised by Jason Farrell
	//Revision Number 3 CopyRight 2005 Help Desk Reloaded
	
	session_start();
	include_once "../config.php";
	
	//this is a worker page, it dispaly no output
	//but like its counter parts for the other search engines, presents the results in a common format
	//for the subsequent page to read
	$key_array = preg_split("/(, ?| )/", $_POST['keys']);
	if (!count($key_array) || empty($_POST['keys'])) {
		$_SESSION['error_msg'] = "You Did Not Provide a List of Keywords";
		header("Location: getResults.php");
	}
	
	//trim the array
	$key_array = array_map("trim", $key_array);
	
	//if the key entry is one, there is a possibility that it could be empty
	
	//begin building the query
	/*
		generate an array of the pertient information for the result
		ticket ID ::t
		description - result (highlight search strings)
		
		ID will be a link to the viewTicket page
		substring the description and resolution, DO NOT SHOW one if they do not contain the word
		Remeber all of this is figured out on this page
	*/
	
	//connect to the database
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS) or die("MySQL Server Could Not be Resolved");
	mysql_select_db(DB_DBNAME) or die("Could Not Select the Database");
	include_once "./includes/functions.php";
	checkKBVisibility();
	
	//query building - get results with matching descriptions
	$q  = "select distinct id, descrip, pageView from " . DB_PREFIX . "data where (";
	$q .= "descrip LIKE '%" . $key_array[0] . "%'";
	
	if (isset($key_array[1])) {
		$inc = 1;
		do
		{
			$q .= " OR descrip LIKE '%" . $key_array[$inc] . "%'";
			$inc++;
		} while ($inc < count($key_array));
	}
	if (isset($_SESSION['enduser'])) {
		$u = unserialize($_SESSION['enduser']);
		if ($u->get('securityLevel', 'intval') < TECH_SECURITY_LEVEL) {
			$q .= " and ticketVisi = 1";
			$supp = " and ticketVisi = 1";
		}
		else {
			$supp = "";	
		}
	}
	else {
		$q .= " and ticketVisi = 1";
		$supp = " and ticketVisi = 1";
	}
	$q .= ") order by pageView desc";
	
	$s = mysql_query($q) or die("Query Failed - " . mysql_error() . "<br/>" . $q);
	/*
		We are going to construct an array of forced enumeration.  Based on common order of the database, the results will comeback to us
		in the proper order to create a queue - we will use this to create an array structure as shown below (initally):
		[id] => [description]
			      = >the description is sbustringed 300 characters from the 150th (or the beginning) character prior to first occurence of the search string
			    [keys] => 
			    	[key1] => int
			    	...
			    [keys_found] => int
			    [sum_of_instances] => int
			    [pageView] => int
			    [files] = array
			    
		This clearly defines the hierachy or order which we will build into out sort function
	*/

	$_retArray = array();
	$_retArray = buildArray($s, $key_array, $_retArray);
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//now we want to search through the resolutions for matching keywords and add those into the array
	//3 stage process
	
	//first we need to build an array of ids that we have already found and exclude them
	foreach ($_retArray as $index)
		$no_id_use[] = "id <> " . $index['id'];
		
	//next build a distinct list of IDs that have the desired solution keyword mix
	$q  = "select distinct id from " . DB_PREFIX . "resolution where (";
	if (isset($no_id_use) && count($no_id_use)) $q .= "(" . implode(' AND ', $no_id_use) . ") AND ";
	$q .= "(solution LIKE '%" . $key_array[0] . "%'";
	
	if (isset($key_array[1])) {
		$inc = 1;
		do
		{
			$q .= " OR solution LIKE '%" . $key_array[$inc] . "%'";
			$inc++;
		} while ($inc < count($key_array));
	}
	$q .= ") )";
	
	//using these ids we will call data from the data table and build in a function paradigm
	$s = mysql_query($q) or die("error");
	$ids = array();
	while ($r = mysql_fetch_assoc($s)) $ids[] = "id = " . $r['id'];	//this builds the array
	
	//build the new query
	if (count($ids)) {
		$q  = "select distinct id, descrip, pageView from " . DB_PREFIX . "data where (";
		$q .= implode(' OR ', $ids);
		if (!isset($_SESSION['enduser'])) $q .= $supp;
		$q .= ")";
		$s = mysql_query($q) or die("error in second query");
		$_retArray = buildArray($s, $key_array, $_retArray);		//this is stage 3
	}
	
	//now we go after the files - this will return us a ticket ID - well check if it is in the array
	//if it is not well add it - this will be a new subkey array for the filenames
	if (isset($_POST['includeFiles'])) {
		$_SESSION['useFiles'] = true;
		/*
			well check for files names matching the keys
		*/
		$files = array();
		if (count($key_array)) {
			$q  = "select f.id, f.name from " . DB_PREFIX . "files f, " . DB_PREFIX . "data d where ((";
			$q .= "f.name like '%" . implode('%\' or f.name like \'%', $key_array) . "%')$supp and f.id = d.id)";
			$s = mysql_query($q) or die(mysql_error());
			
			while ($r = mysql_fetch_assoc($s))
				$_retArray[$r['id']]['files'][] = $r['name'];
		}
	}
	
	$_retArray = fillResultsArray($_retArray);
	
	//begin the final counting with inclusion of filenames (possibly)
	foreach ($_retArray as $id => $array)
	{
		//inerarray loop
		foreach ($array['files'] as $filename)
		{
			//perform the search
			foreach ($key_array as $key)
			{
				if (preg_match("/$key/i", $filename)) {
					if (!isset($_retArray[$id]['keys'][$key])) $_retArray[$id]['keys'][$key] = 0;
					$_retArray[$id]['keys'][$key]++;
					$_retArray[$id]['keys_found']++;
					$_retArray[$id]['id'] = $id;
				}
			}	
		}
	}
	
	//sort the array
	uasort($_retArray, "keyCmp");
	$_SESSION['infoArray'] = $_retArray;
	
	#print "<pre>";
	#print_r($_retArray);
	#print "</pre>";
	#exit;
	
	//redirect
	header("Location: getResults.php");
?>