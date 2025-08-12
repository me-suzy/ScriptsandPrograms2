<?php

/*  
    Search Results
 	(c) 2005 Philip Shaddock, www.wizardinteractive.com
	This file is part of the Wizard Site Framework.

    This file is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    It is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with the Wizard Site Framework; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// main configuration file
	include_once '../../inc/config_cms/configuration.php';
// database class
	include_once '../../inc/db/db.php';
// language translation
	include_once '../../inc/languages/' . $language . '.public.php';
	// configuration parameters
	$db = new DB();
	$db->query("SELECT * FROM ". DB_PREPEND . "config");
	$config = $db->next_record();
	$db->close();	


$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$organization = $_GET['organization'];
$email = $_GET['email'];
   ?>
 <!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Registration Form</title>
<link href="<?php echo CMS_WWW; ?>/templates/css/basestyles.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/ico" href="<?php echo CMS_WWW; ?>/favicon.ico" />
</head>

<body id="bd">

<!--Outside Table-->
	<table align="center" cellspacing="0" id="alertTable">
	<tr>
	<td>
	<table width="600px" align="center" border="0" cellpadding="1" cellspacing="0" >
      	<tr>
			<td colspan="2">
	  			<table align="center" width="100%" border="0" cellpadding="5" cellspacing="0" >
      				<tr>
        				<td id="alertHeader">Search</td>
      				</tr>
				</table>
			</td>
		</tr>
		
		
<?

	// form input //
    
	$searchstring = $_GET['term'];
	$searchstring = stripslashes(htmlspecialchars($searchstring));
		
	// passed from one page to another
	if (!$searchstring) {
	    $searchstring = $_GET['passedTerm'];
	}
	
	if (!$searchstring) {
	    $searchstring = S_EMPTY_FORM;
	}
	
	// number of results to display on a page
	$limit = 20;
	
	//for paging, check to see if this is the first time this been run
    	$page = $_GET['page'];
		if(empty($page)){ 
        	$page = 1; 
    	} 
	
	
	//set this using admin - permissions setting
	if ($config['searchRestrict'] == "off") {
	$permit = "AND permit=4";
	}

	
	//don't do a total rows search if you have done so on the previous page
	if (!$total_rows) {	
	$sql = "SELECT *, UNIX_TIMESTAMP(date),
            MATCH(description) AGAINST ('$searchstring') AS score 
        FROM ". DB_PREPEND . "pages  
        WHERE admin='0' AND menu='on' " .$permit. " AND
		MATCH(description) AGAINST('$searchstring')"; 
		
		// data query //
		$db = new DB();
		$result = $db->query($sql);
		$total_rows = $db->num_rows();

	    
	} // if total_rows
	
	
	$limitvalue = $page * $limit - ($limit); 
	
	$sql = "SELECT *, UNIX_TIMESTAMP(date),
            MATCH(description) AGAINST ('$searchstring') AS score 
        FROM ". DB_PREPEND . "pages  
        WHERE admin='0' AND menu='on' " .$permit. " AND
		MATCH(description) AGAINST('$searchstring') 
		LIMIT $limitvalue, $limit\n";
		

	// data query //
	$db = new DB();
	$result = $db->query($sql);
	
	echo "<tr><td>";
	echo "<div id=\"search\" align=\"left\">";
	echo "<table align=\"center\" style=\"border-collapse: collapse\" border=\"0\" cellpadding=\"20\" border=\"0\" border-color=\"eeeeee\" width=\"100%\"><tr><td>";
	echo "<table align=\"center\" style=\"border-collapse: collapse\" border=\"0\" cellpadding=\"0\" border=\"0\" border-color=\"eeeeee\" width=\"100%\">";
		
	
	    echo "<tr><td>";
		echo "<table><tr><td>Search Again: </td><td><form name=\"search\" method=\"get\" action=\"" . CMS_WWW . "/templates/forms/search_results.php" . "\">"; 				
				echo "<input type=\"text\" name=\"term\" size=\"20\" />";
				echo "</td><td><input type=\"image\" name=\"term\" src=\"" . CMS_WWW . "/images/common/search.gif\" style=\"border:none\" align=\"bottom\" width=\"42\" height=\"14\" /></table></td></tr>";
				
		echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";		
				
		//Title
		echo "<tr><td class=\"largerText\"><hr />Results: <span class=\"smallText\">";
		if ($total_rows >= 1) {
	    	echo "<font color=\"#707070\"><span class=\"smalltext\">" . $total_rows . "&nbsp; pages found for </span></font>";
		}
		else {
	    	echo "<font color=\"#707070\"><span class=\"smalltext\">no pages found</span></font>";
		}
	    echo " &nbsp;<span class=\"message\"><b>$searchstring</b></span></td>";
	echo "</tr>";
	
	echo "<tr><td>&nbsp;</td></tr>";
	
	if (!$total_rows) {
	    echo "<tr><td>".S_NO_RESULTS."</td></tr>";
	}
	else {
	
	    //for numbering items
	    $num = $_GET['num'];
	    if (!$num) {
	        $num = 0;
	    }
		
		while($i = $db->next_record()){
	    	// results numbering
			$num = $num + 1;
			$site_www = CMS_WWW;
			echo "<tr>";
				echo "<td class=\"smallText\">" .$num .".&nbsp;<a href=\"" . $site_www . "/pages/". $i['filename']  . "?id=" .  $i['id']      ."\">$i[title]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: " .  $i['date'] . "</td>"; 
			echo "</tr>"; 
			$txt = ereg_replace ('/&\w;/', '', strip_tags($i[description]));
			$summary = substr($txt, 0, 200);
			echo "<tr>";
				echo "<td class=\"smallText\">" . $summary . "<hr /></td>"; 
			echo "</tr>"; 
		 	
			
		} // while
		echo "<tr><td>&nbsp;</td></tr>";
	} // if result

//do not show navigation box is there is 0 or 1 results
if ($total_rows > $limit) {

//Page Navigation Links
	echo("<tr><td><table align=\"left\" bgcolor=\"#FFFFFF\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\"  width=\"10%\">"); 
	echo "<tr><td>";
	echo("<table align=\"left\" bgcolor=\"#FFFFFF\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" style=\"border-collapse: collapse\"  width=\"10%\">"); 
    echo "<tr class=\"smalltext\">";
	
	//Previous arrow link
	$page = $_GET['page'];
	
	$pageRoot = CMS_WWW . "/index.php?id=4&table=". DB_PREPEND . "pages&num=$num&total_rows=$total_rows&passedTerm=$searchstring";
	
	if(empty($page)){ 
        $page = 1; 
    }  
	
	if($page != 1){ 
        $pageprev = $page-1; 
         
        echo("<td class=\"tinytext\"><a href=\"". $pageRoot . "&page=$pageprev\">".S_PREV."&nbsp;</a></td>"); 
		echo "<td><a href=\"". $pageRoot . "&page=$pageprev\"><img alt=\"".S_PREVIOUS_PAGE."\" src=\"admin/images/pageleft.gif\" width=\"12\" height=\"13\" border=\"0\"></a></td>";

    }
	

	
	//Page Numbers	
    echo "<td>";
	
    $numofpages = $total_rows / $limit; 
     
    for($i = 1; $i <= $numofpages; $i++){ 
        if($i == $page){ 
            echo($i."&nbsp;"); 
        }else{ 
            echo("<a href=\"". $pageRoot . "&page=$i\"><font color=\"Red\">$i</font></a>&nbsp;"); 
        } 
    } 
    echo "</td>";

	// Last Page Number
	echo "<td>";
    if(($total_rows % $limit) != 0){ 
        if($i == $page){ 
            echo($i."&nbsp;"); 
        }else{ 
            echo("<a href=\"". $pageRoot . "&page=$i\"><font color=\"Red\">$i</font></a>&nbsp;"); 
        } 
    } 
	echo "</td>";
	
	
	//Next
    if(($total_rows - ($limit * $page)) > 0){ 
        $pagenext = $page+1; 
        echo "<td><a href=\"". $pageRoot . "&page=$pagenext\"><img alt=\"".S_NEXT_PAGE."\" src=\"admin/images/pageright.gif\" width=\"12\" height=\"13\" border=\"0\"></a></td>"; 
        echo("<td class=\"tinytext\"><a href=\"". $pageRoot . "&page=$pagenext\">".S_NEXT."&nbsp;</a><td>"); 
    }
	
    echo("</tr></table>"); 
	echo("</table>");
	echo "</td></tr>";
	echo "</td></tr></table>";
	echo("</table></td></tr>");
    
}	
   
echo "</table></div>";

?>
<p align="center"><a href="<?php echo CMS_WWW; ?>">Home</a></p>
</td></tr>
</table>

</td></tr>
</table>
</body>
</html>