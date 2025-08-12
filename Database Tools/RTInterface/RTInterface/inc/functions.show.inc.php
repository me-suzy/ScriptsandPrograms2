<?php
/****************************************************************************
This file contains some functions to show an entry from a table
****************************************************************************/

/*******************************************************************
Show a selected table. Use the session vars
current_table, order_by, current_page, current_rows_per_page to perform a select
********************************************************************/
function showTable(){

	$tableObject = new $_SESSION["current_table"]();
	$tableObject->sql_orderby = $_SESSION["order_by"];
	$tableObject->pageno = $_SESSION["current_page"];
	$tableObject->rows_per_page = $_SESSION["current_rows_per_page"];
	$tableObject->getData();

	popup(); //Initialize a javascript popup

	echo "<p class=\"actionTitle\" align=center>Showing entire table {$_SESSION["current_table"]} </p>";

	showActions("show"); //Show the possible actions

	if(empty($tableObject->data_array)){
		echo "<br>No element found<br>";
	}
	else //Elements found
	{
		// This loop build up an html table with data_array as data
		echo "<table width=\"100%\" border=\"0\">
			<tr>";
		//Stampa i titoli
		foreach($tableObject->data_array as $row)
		{
			foreach($row as $type=>$value){
				echo "<td class=\"tableTitle\"><a href=\"show.php?order_by={$type}\">:{$type}:.</a></td>";
			}
			break;
		}
		echo "</tr>";
		foreach($tableObject->data_array as $row)
		{
			echo "<tr>";


			foreach($row as $key=>$value){
				// If it's a primary key show a link to modify the element. If it's a text show a link to open a popup
				if($tableObject->fieldspec[$key]['pkey'])
				echo "<td><a href=\"update.php?current_table={$_SESSION["current_table"]}&detail_id={$value}&detail_type={$key}\">{$value}</a></td>";
				else if($tableObject->fieldspec[$key]['type'] == 'text'){
					echo "<td ><a href=\"javascript:Popup('popup.php?value=."; echo nl2br($value)."')\">Apri popup</a></td>";
				}
				else
				echo "<td>$value</td>";
			}
			echo "</tr>";
		}

		echo "</table>";

		// Calculate the link to jump to other pages of the selection

		$current = $tableObject->pageno;
		$prev = $tableObject->pageno - 1;
		$next = $tableObject->pageno + 1;
		$last = $tableObject->lastpage;
		$first = 1;

		echo "<p class=\"navLink\" align=right> Page {$current} of {$last}.";
		if($prev != 0 || $last >= $next)
		echo " Go to: ";
		if($prev != 0){
			echo  "<a href=\"show.php?current_page=1\">First</a> - <a href=\"show.php?current_page={$prev}\">Prev</a>"	;
		}
		if($last >= $next){
			if($prev != 0)
			echo " - ";
			echo "<a href=\"show.php?current_page={$next}\">Next</a> - <a href=\"show.php?current_page={$last}\">Last</a>";
		}
		echo "</p>";

	} //ELSE

}




?>