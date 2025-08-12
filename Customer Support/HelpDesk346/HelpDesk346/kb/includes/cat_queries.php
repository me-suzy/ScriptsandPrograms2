<?php
	define('QUERY_LIMIT', 10);
	
	//Variables for Defining Filtering if the user is not logged in
	if (!isset($_SESSION['enduser'])) {
		$cond = "where ticketVisi = 1";
		$supp = "and ticketVisi = 1";
	}
	else {
		$u = unserialize($_SESSION['enduser']);
		if ($u->get('securityLevel', 'intval') > ENDUSER_SECURITY_LEVEL)
			$cond = $supp = "";
		else {
			$cond = "where ticketVisi = 1";
			$supp = "and ticketVisi = 1";
		}
	}
	
	/*
		Query Set 1 - Parent Count Gathering Queries
		
		Query 1:
		Grab all distinct categories from the data table and the number of them
		
		Query 2:
		Grab all distinct priorities from the data table and the number of them
		
		Query 3:
		Grab all distinct statuses from the data table and the number of them
		
		Database Designers Note:
		This system is utterly horrible as it does not enforce any kind of referential integrity or foreign key contraints. A concise effort
		should be made to redesign the database so that categories and priorities and status' are referenced properly/
	*/

	define('QUERY_1', "select distinct category, count(category) as c from " . DB_PREFIX . "data $cond group by category");
	define('QUERY_2', "select distinct priority, count(priority) as c from " . DB_PREFIX . "data $cond group by priority");
	define('QUERY_3', "select distinct status, count(status) as c from " . DB_PREFIX . "data $cond group by status");
	
	/*
		Query Set 2 - LIMIT Selection Conditional Queries
		
		All Queries here are defined only if a certain variable is present - in _GET
	*/
	if (isset($_GET['item']) && empty($_GET['item'])) $_GET['item'] = '';
	
	if (isset($_GET['item']) && !isset($_GET['limit'])) {
		define('QUERY_4', "select id from " . DB_PREFIX . "data where category = '" . intval($_GET['item']) . "' $supp  order by pageView LIMIT " . QUERY_LIMIT);
		define('QUERY_5', "select id from " . DB_PREFIX . "data where priority = '" . intval($_GET['item']) . "' $supp  order by pageView LIMIT " . QUERY_LIMIT);
		define('QUERY_6', "select id from " . DB_PREFIX . "data where status = '" . intval($_GET['item']) . "' $supp  order by pageView LIMIT " . QUERY_LIMIT);
	}
	elseif (isset($_GET['item'])) {
		define('QUERY_4', "select id from " . DB_PREFIX . "data where category = '" . intval($_GET['item']) . "' $supp order by pageView");
		define('QUERY_5', "select id from " . DB_PREFIX . "data where priority = '" . intval($_GET['item']) . "' $supp order by pageView");
		define('QUERY_6', "select id from " . DB_PREFIX . "data where status = '" . intval($_GET['item']) . "' $supp order by pageView");
	}
?>