<?php
/****************************************************************************
This file contains some general function for the graphic interface
****************************************************************************/
require_once("./inc/functions.update.inc.php");
require_once("./inc/functions.insert.inc.php");
require_once("./inc/functions.search.inc.php");
require_once("./inc/functions.show.inc.php");
require_once("./inc/functions.delete.inc.php");


/********************************************************************
Show title and logo from cfg.inc.php
********************************************************************/
function showTitleImage(){

	global $main_title, $main_image;

	echo "<p class=\"title\" align=center>";
	if($main_image != ""){
		echo "<img src=\"{$main_image}\" border=\"0\" alt=\"Logo\" align=center><br>";
	}
	echo "{$main_title}</p>";

}

/********************************************************************
Show possible action for the selected elements and a link to change the number of rows per page.
Submit a string set to the current action("update","show","insert","search")
********************************************************************/
function showActions($function){
	switch($function){
		CASE "show":
		echo "<p class=\"navLink\" align=right>Possible action: <a href=\"search.php\"><b>Search</b></a>";
		echo " .:. <a href=\"printsel.php\" target=\"_blank\"><b>Printer friendly version</b></a>";
		echo " .:. <a href=\"insert.php\"><b>Insert new element</b></a><br>";

		$count = 0; //for visualization parameters
		$numPage=Array(2,5,25,50,100);

		echo "Rows per page:
			";
		foreach($numPage as $value){
			if($count != 0)
			echo " .:. ";
			if($_SESSION['current_rows_per_page'] == $value)
			echo "$value";
			else
			echo "<a href=\"show.php?current_rows_per_page=$value\">$value</a>";
			$count++;
		}
		echo "</p>";
		break;
		CASE "update":
		echo "<p class=\"navLink\" align=right>Possible actions: <a href=\"search.php\"><b>Search</b></a>";
		echo " .:. <a href=\"show.php\"><b>Show entire table</b></a> .:. <br>";
		echo " .:. <a href=\"printel.php\" target=\"_blank\"><b>Printer friendly version</b></a>";
		echo " .:. <a href=\"delete.php\"><b>Delete current element</b></a>";
		echo " .:. <a href=\"insert.php\"><b>Insert new element</b></a>";
		echo "</p>";
		break;
		CASE "insert":
		CASE "delete":
		echo "<p class=\"navLink\" align=right>Possible actions: <a href=\"search.php\"><b>Search</b></a>";
		echo " .:. <a href=\"show.php\"><b>Show entire table</b></a>";
		echo " .:. <a href=\"insert.php\"><b>Insert new element</b></a>";
		echo "</p>";
		break;
		CASE "search":
		echo "<p class=\"navLink\" align=right>Possible actions: <a href=\"show.php\"><b>Show entire table</b></a>";
		echo " .:. <a href=\"printsels.php\" target=\"_blank\"><b>Printer friendly version</b></a>";
		echo " .:. <a href=\"insert.php\"><b>Insert new element</b></a><br>";
		$count = 0; 
		$numPage=Array(2,5,25,50,100);

		echo "Rows per page:
			";
		foreach($numPage as $value){
			if($count != 0)
			echo " .:. ";
			if($_SESSION['current_rows_per_page'] == $value)
			echo "$value";
			else
			echo "<a href=\"search.php?submit_search=true&current_rows_per_page=$value\">$value</a>";
			$count++;
		}
		echo "</p>";
		break;
		default:
		break;
	}


}

/********************************************************************
Show the title for the selected table
********************************************************************/
function showTableName(){

	echo "<p class=\"subTitle\" align=left>Current table: <b>{$_SESSION['current_table']}</b></p>";
}

/********************************************************************
Link to all existing table. To use in header.inc.php
********************************************************************/
function showTableList(){
	$count = 0; //for visualization loop
	global $table_list;


	echo "<table align=center width=\"80%\"><tr><td class=\"tableLink\"><b>Table in the database:</b>
	</td></tr></table>";	
	echo "<table align=center width=\"80%\"><tr>";

	foreach($table_list as $value){
		$count++;
		if($_SESSION['current_table'] == $value)
		echo "<td class=\"tableLink\">$value
				 </td>";
		else
		echo "<td class=\"tableLink\"><a href=\"show.php?current_table=$value&new_function=true\">$value</a>
				 </td>";
		if($count%5 == 0)
		echo "</tr>
					<tr>";

	}
	echo "</table>";
}



/********************************************************************
Returns the name of the current table
********************************************************************/
function getCurrentTableName(){

	return strtoupper($_SESSION['current_table']);

}

/********************************************************************
Initialize a javascript popup function
********************************************************************/
function popup(){
	print '
	<script type="text/javascript">
	 <!--
	  var stile = "top=10, left=10, width=650, height=490, resizable=yes, status=no, menubar=no, toolbar=no scrollbar=yes";
 	    function Popup(apri) {
 	       window.open(apri, "", stile);
 	    }
	 //-->
	</script>';

}


?>