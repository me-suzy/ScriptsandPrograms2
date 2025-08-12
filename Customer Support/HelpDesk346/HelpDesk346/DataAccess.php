<?php
	//This is good ol american code
	//Revised July 16, 2005
	//Revised by Jason Farrell
	//Revision Number 1
	
	include_once("checksession.php");
	include_once "./includes/classes.php";
	include_once "./includes/settings.php";
	
	if (!isset($_SESSION['adder']))
		$_SESSION['adder'] = 0;
		
	$adder = $_SESSION['adder'];
	
	//process adder
	if (isset($_GET['cmd']) && $_GET['cmd'] == 'back') {
		$adder = ($adder - $OBJ->get('result_page', 'intval')) < 0 ? 0 : $adder - $OBJ->get('result_page', 'intval');
	}
	else if (isset($_GET['cmd']) && $_GET['cmd'] == 'next') {
		$adder += $OBJ->get('result_page', 'intval');	
	}
	else if (isset($_GET['push'])) {
		$adder = $OBJ->get('result_page', 'intval') * intval($_GET['push']);	
	}
	
	$_SESSION['adder'] = $adder;
?>
<html>
	<head>
		<title>Help Desk Main Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		 
	</head>
	<body>
<table width="97%" height="412" border="0" cellpadding="0" cellspacing="0">
  <tr valign="top"> 
    <td width="25%" colspan="2">
<?php
	//Check for Navtype
	#print "<pre>";
	#var_dump($OBJ);
	#print "</pre>";
	#exit;
	if($OBJ->get('navigation') == 'B') {
		include 'dataaccessheader.php';
	}
	else {
		include 'textnavsystem.php';
	}
?>
	
      <table width="99%" border="1" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="73%" rowspan="2" valign="top">This page shows open Help Desk 
            calls only. Please click on View All Calls to view all past support 
            calls.<a href="default.htm"><br>
            <br>
            </a> 
<?php	 
	if (isset($_GET["orderby"]))
		$orderby = $_GET["orderby"];
		
	if (isset($_GET['order']) && !empty($_GET["order"]))		
		$order = $_GET["order"];
	
	if(empty($orderby))	 $orderby = "ID";
	if(empty($order))    $order = "asc";
	
	if ($order == "asc") 
		$order = "desc";
	else if ($order == "desc") 
		$order = "asc";
	
	//because we want to hide tickets whoa re closed, we need to find tickets that are not at the last stage
	#$q = "select id from " . DB_PREFIX . "status order by position desc limit 1";
	#$lastStat = mysql_result(mysql_query($q), 0);
	
	//here we check for the filter
	if (isset($_GET['filter']) && $_GET['filter'] == 'user') {
		$u = unserialize($_SESSION['enduser']);
		echo "Viewing Helpdesk Calls for " . $u->get('FirstName', 'stripslashes') . ' ' . $u->get('LastName', 'stripslashes');
		if ($orderby == 'Priority' || $orderby == 'PCatagory' || $orderby == 'Status') {
			$supp = 'and staff = ' . $u->get('id');
		}
		else {
			$supp = 'where staff = ' . $u->get('id');
		}	
	}
	else  {
		echo "Updated version of database (after entries)"; 
		$supp = '';
	}
	
	switch ($orderby)
	{
		case 'Priority':
			$sql = "select ID from " . DB_PREFIX . "data d, " . DB_PREFIX . "priorities p where d.priority = p.pid $supp order by p.severity $order";
			break;
		case 'PCatagory':
			$sql = "select d.ID from " . DB_PREFIX . "data d, " . DB_PREFIX . "categories c where d.category = c.id $supp order by c.name $order";
			break;
		case 'Status':
			$sql = "select d.ID from " . DB_PREFIX . "data d, " . DB_PREFIX . "status s where d.status = s.id $supp order by s.position $order";
			break;
		default:
			$sql = "select ID from " . DB_PREFIX . "data $supp order by $orderby $order";
			break;
	}

	#if(!isset($_GET['ppage'])) $_GET['ppage']=1;
	
	$s = mysql_query($sql) or die(mysql_error());
	if (mysql_num_rows($s))
	{	
		echo "<table border=1>
				<tr>
					<th><a href='DataAccess.php?orderby=ID&order=$order'>ID</a></th>\n
					<th><a href='DataAccess.php?orderby=mainDate&order=$order'>Time & Date</a></th>\n
					<th><a href='DataAccess.php?orderby=FirstName&order=$order'>First Name</a></th>\n
					<th><a href='DataAccess.php?orderby=LastName&order=$order'>Last Name</a></th>\n
					<th><a href='DataAccess.php?orderby=Status&order=$order'>Help Request Status</a></th>\n
					<th><a href='DataAccess.php?orderby=PCatagory&order=$order'>Type of Problem</a></th>\n
					<th><a href='DataAccess.php?orderby=Priority&order=$order'>Priority</a></th>
				</tr>\n"; 
	 	    // fetch the succesive result rows
	 	    $cycles = 0;
	 	    
	 	    // this is about as hackish as it gets, but we want to generate the status id with the highest position
	 	    // create a status object as use it as a comparison within the loop to skip closed calls while filter is not set
	 	    if (isset($_GET['filter']) && $_GET['filter'] == 'active') {
	 	    	$q = "select id from " . DB_PREFIX . "status order by position desc LIMIT 1";
	 	    	$_s = mysql_query($q) or die(mysql_error());
	 	    	$s1 = new Status(mysql_result($_s , 0));
	 	    }
	 	    
			while ( ($adder + $cycles) < mysql_num_rows($s))
			{
				$t = new Ticket(mysql_result($s, $adder+$cycles));
				$display = true;
				if (isset($_GET['filter']) &&$_GET['filter'] == 'active') {
					$s2 = $t->get('status');
					if ($s1->get('id', 'intval') == $s2->get('id', 'intval')) {
						$display = false;
					}	
				}
				
				if ($display)
				{
					//status check
					if ($OBJ->get('req_image', 'intval')) {
						$stat = $t->get('status');
						if (is_object($stat)) {
							$icon = '<img src="images/' . $stat->get('icon', 'stripslashes') . '" width="24" height="23" />';;
							$color = $stat->get('color', 'stripslashes');
						}
						else
							$icon = $color = '';
					}
					else {
						$icon = $color = '';	
					}
					
					if(!$OBJ->get('req_image')) $icon="";
					
					echo "<tr>
							<td><a href=\"viewDetails.php?record=" . $t->get('id') . "\">" . $t->get('id') . "</a></td>\n
							<td>" . $t->get('mainDate') . "</td>\n
							<td>" . $t->get('FirstName') . "</td>\n
						    <td>" . $t->get('LastName') . "</td>\n";
					$stat = $t->get('status');
					$c = $t->get('PCatagory');
					$p = $t->get('priority');
					echo "  <td>$icon&nbsp<font color='$color'>" . $stat->get('name', 'stripslashes') . "</font></td>
						    <td>" . $c->get('name', 'stripslashes') . "</td><td>" . $p->get('name', 'stripslashes')  . "</td>\n
						  </tr>\n";
				}
				
				$cycles++;
				if ($cycles == $OBJ->get('result_page', 'intval')) break;
			 }
		
			echo "<tr>\n
					<td colspan=7 align=center>\n
						<strong>" . mysql_num_rows($s) . " entries</strong>\n
					</td>\n
				  </tr>\n";
			//Give links to next page
			echo "<tr>\n
					<td colspan=7 align=center>\n
						<table cellpadding=0 cellspacing=0 border=0 align=center width=\"75%\">\n
							<tr>
								<td align=left width=\"25%\" style=\"font-family:Verdana; font-size:12px\" valign=top>\n";
			if (isset($_GET['filter']) && $_GET['filter'] == 1) $supp = "&filter=1";
			if ($adder > 0)
				echo "<a href='?cmd=back" . $supp . "'><<< Previous</a>";
			else
				echo "";
			
			echo "				</td>\n
								<td width=\"50%\">\n";
			
			$total_rows = mysql_num_rows($s);
			$total_sections = ceil($total_rows / $OBJ->get('result_page', 'intval'));
			for ($i = 1; $i<=$total_sections; $i++) {
				if ($adder == ($i - 1) * $OBJ->get('result_page', 'intval')) echo "<b>";
				echo "<a href='?push=" . ($i - 1) . $supp . "'>" . $i . "</a></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			
			echo "					</td>\n
								<td width=\"25%\" valign=top>\n";
			if ($adder < (mysql_num_rows($s) - $OBJ->get('result_page', 'intval'))) {
				//ECHO
					echo "<a href='?cmd=next" . $supp . "'>Next >>></a>";
			}
			else echo "";
			echo "		</td></tr>\n
					</table>\n";
		  }
		  else//else no records found
		  	echo "<div align=center><font size=2 color=#ff0000 face=verdana><strong>No Records Found</strong></font></div>";
?>
          </td>
         </tr>
         <tr>
          <td colspan="7">
          	<div align="center"><strong><font size="2">Other Help Desk Tools:</font></strong></div>
          	<p align="center">&nbsp;</p>
            <p align="center"><a href="actmgt.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Help 
              Desk Control Panel</strong></font></a></p>
            <p align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="logout.php"><strong>Log 
              Completely off from Help Desk.</strong></a></font></p>
          </td>
          <td valign="top"></td>
        </tr>
      </table> 
      <div align="center">
        
        <br>
        <br>
        <br>
        <a href="http://www.helpdeskreloaded.com"><img src="http://www.helpdeskreloaded.com/reload/help-desk-copyright.jpg" alt="http://www.helpdeskreloaded.com Help Desk Software By  HelpDeskReloaded &quot;Help Desk Reloaded&quot;" border="0"></a></div></td>
  </tr>
</table>
<br>
<br>
<p> 
  <map name="Map">
    <area shape="rect" coords="5,142,77,194" href="reportproblem.php">
    <area shape="rect" coords="75,147,121,196" href="helpDeskAccessAllCalls.php">
    <area shape="rect" coords="127,147,182,197" href="DataAccessSearch.php">
    <area shape="rect" coords="188,146,276,196" href="ocm-first.htm">
    <area shape="rect" coords="279,146,361,194" href="actmgt.php">
    <area shape="rect" coords="478,146,540,198" href="search.php">
    <area shape="rect" coords="542,145,610,197" href="default.htm">
  </map>
</p>
</body>