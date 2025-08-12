<?php
/****************************************************************************
This file contains some functions to search on the database
****************************************************************************/

/*******************************************************************
Show the form for the search
********************************************************************/
function showSearch(){

	$tableObject = new $_SESSION["current_table"]();

	echo "<p class=\"actionTitle\" align=center>Search elements from table {$_SESSION["current_table"]}</p>";

	showActions("search");

	echo "<table width=\"100%\">";
	echo "<form name=\"searchForm\" method=\"post\" action=\"search.php\">";

	foreach($tableObject->fieldspec as $key=>$value)
	{

		$required = '';//Add "not null" 
		if($tableObject->fieldspec[$key]['required'])
		$required = '(Not null)';

		echo "<tr>";

		if($value['pkey']){
			$keydisplay = strtoupper($key);
			echo "<td>{$keydisplay} (Primary key): </td><td><input type=\"text\" name=\"{$key}\" value=\"\">
				</td>";
		}//IF
		else{
			$relationship = false; //To check if the element has relations
			// If the key has some relations build up a menu
			foreach($tableObject->relationship_out as $keyr){
				if(isset($keyr['fields'][$key])){
					foreach($keyr['fields'] as $many=>$one){

						$relationship = true;
						$tableObjectrel = new $keyr['one']();
						$tableObjectrel->sql_select = $one;
						$tableObjectrel->getData();
						$keydisplay = strtoupper($key);
						echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
						echo "<option value=\"\" selected> </option>";
						foreach($tableObjectrel->data_array as $rowsel){
							foreach($rowsel as $keysel=>$valuesel){
								echo "<option value=\"$valuesel\"";
								echo">$valuesel</option>";
							} //FOREACH
						} //FOREACH
						echo "</select>
								</td>";
					}//FOREACH

				}//IF
			}//FOREACH

			// If the key has no relation ..
			if(!$relationship){
				$keydisplay = strtoupper($key);
				switch ($tableObject->fieldspec[$key]['type']){
					CASE "boolean":
					echo "<td>{$keydisplay}{$required}(booleano): </td><td><select name=\"{$key}\"><option value=\"\" selected> </option>";
					echo "<option value=\"{$tableObject->fieldspec[$key]['true']}\">true - {$tableObject->fieldspec[$key]['true']}</option>
							<option value=\"{$tableObject->fieldspec[$key]['false']}\">false - {$tableObject->fieldspec[$key]['false']}</option>
							</select>
							</td>";
					break;
					CASE "enum":
					echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
					echo "<option value=\"\" selected> </option>";
					foreach($tableObject->fieldspec[$key]['enum'] as $enumvalue){
						echo "<option value=\"$enumvalue\"";
						echo">$enumvalue</option>";
					}
					echo "</select>
								</td>";
					break;
					CASE "date":
					echo "<td>{$keydisplay}{$required}: </td><td><input type=\"text\" name=\"{$key}\" value=\"\">(date DD-MM-YYYY)
							</td>";						
					break;
					CASE "time":
					echo "<td>{$keydisplay}{$required}: </td><td>(time HH:MM:SS)<input type=\"text\" name=\"{$key}\" value=\"\">
							</td>";						
					break;
					CASE "textarea":
					echo "<td>{$keydisplay}{$required}: </td><td><input type=\"textarea\" name=\"{$key}\" value=\"\">
							</td>";	
					break;
					CASE "varchar":
					CASE "int":
					default:

					echo "<td>{$keydisplay}{$required}: </td><td><input type=\"text\" name=\"{$key}\" value=\"\">
							</td>";
					break;
				} //SWITCH
			} //IF

			echo "</tr>";
		}//ELSE no pkey
	}//FOREACH
	echo "<tr><td><input type=\"submit\" name=\"submit_search\" value=\"Search\">";
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";
}// ShowSearch

/**********************************************************
Show the results
**********************************************************/
function doSearch(){

	$tableObject = new $_SESSION["current_table"]();
	$tableObject->sql_orderby = $_SESSION["order_by"];
	$tableObject->pageno = $_SESSION["current_page"];
	$tableObject->rows_per_page = $_SESSION["current_rows_per_page"];
	$to_search = $tableObject->std_fieldValidation_insert($_SESSION['search_array']);
	$where = $tableObject->searchRecord($to_search);

	popup(); //Initialize javascript function to popup an element
	echo "<p class=\"actionTitle\" align=center>Show the current search:</p>";

	if($where == '')
	$where = "No parameter selected. The search will be performed on the entire table {$_SESSION['current_table']}";
	else
	$where = $where." from table {$_SESSION['current_table']}";

	echo "Search parameters: $where";

	showActions("search"); //Show possible actions

	if(empty($tableObject->data_array)){
		echo "<br>No elements found<br>";
	}
	else //Elements found
	{
		// Print an html table using data_array as data
		echo "<table width=\"100%\" border=\"0\">
			<tr>";

		foreach($tableObject->data_array as $row)
		{
			foreach($row as $type=>$value){
				echo "<td  class=\"tableTitle\"><a href=\"search.php?submit_search=true&order_by={$type}\">:{$type}:.</a></td>";
			}
			break;
		}
		echo "</tr>";
		foreach($tableObject->data_array as $row)
		{
			echo "<tr>";

			//If it's a primary key show a link to modify the key. If it's a text type show a popup to show all the text
			foreach($row as $key=>$value){
				if($tableObject->fieldspec[$key]['pkey'])
				echo "<td><a href=\"update.php?current_table={$_SESSION["current_table"]}&detail_id={$value}&detail_type={$key}\">{$value}</a></td>";
				else if($tableObject->fieldspec[$key]['type'] == 'text'){
					echo "<td ><a href=\"javascript:Popup('popup.php?value=."; echo nl2br($value)."')\">Open popup</a></td>";
				}
				else
				echo "<td>$value</td>";
			}
			echo "</tr>";
		}

		echo "</table>";

		// Calculate links to jump to other pages of the selection

		$current = $tableObject->pageno;
		$prev = $tableObject->pageno - 1;
		$next = $tableObject->pageno + 1;
		$last = $tableObject->lastpage;
		$first = 1;


		echo "<p class=\"navLink\" align=right> Page {$current} of {$last}.";
		if($prev != 0 || $last >= $next)
		echo " Go to: ";
		if($prev != 0){
			echo  "<a href=\"search.php?submit_search=true&current_page=1\">First</a> - <a href=\"search.php?submit_search=true&current_page={$prev}\">Prev</a>"	;
		}
		if($last >= $next){
			if($prev != 0)
			echo " - ";
			echo "<a href=\"search.php?submit_search=true&current_page={$next}\">Next</a> - <a href=\"search.php?submit_search=true&current_page={$last}\">Last</a>";
		}
		echo "</p>";

	} //ELSE

}


?>