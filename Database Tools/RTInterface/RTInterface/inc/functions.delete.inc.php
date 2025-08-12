<?php
/****************************************************************************
This file contains some functions to delete an entry on the database
****************************************************************************/

/*******************************************************************
Show the element that we're going to delete
********************************************************************/
function showDelete(){

	$tableObject = new $_SESSION["current_table"]();
	$tableObject->sql_where = "{$_SESSION["detail_type"]} = '{$_SESSION["detail_id"]}'";
	$tableObject->getData();

	echo "<p class=\"actionTitle\" align=center>Details of the selected element</p>";

	echo "<p align=center>Warning, by clicking here you'll delete permanently the element {$_SESSION['current_table']}</p>";

	showActions("delete");

	echo "<table width=\"100%\">";
	echo "<form name=\"deleteForm\" method=\"post\" action=\"delete.php\">";

	foreach($tableObject->data_array as $row)
	{
		foreach($row as $key=>$value){

			$required = '';//Add * to the key that has not to be null
			if($tableObject->fieldspec[$key]['required'])
			$required = '(Not null)';

			echo "<tr>";

			if($tableObject->fieldspec[$key]['pkey']){
				$keydisplay = strtoupper($key);
				echo "<td>{$keydisplay} (Primary key): </td><td>{$value}
				</td>";
			}//IF
			else if($tableObject->fieldspec[$key]['type'] == 'text'){
				echo "<td>{$keydisplay}{$required}: </td><td>"; echo nl2br($value)."
				    	</td>";
			}
			else{
				$keydisplay = strtoupper($key);
				echo "<td>{$keydisplay}{$required}: </td><td>{$value}
						</td>";
				echo "</tr>";
			}//ELSE no pkey
		}//FOREACH
	}//FOREACH
	echo "<tr><td><input type=\"submit\" name=\"submit_delete\" value=\"Delete\">";
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";
}// ShowUpdate


/*******************************************************************
Check for constraint on the element to delete. If it's all ok delete the element
*******************************************************************/
function doDelete(){
	showActions("delete");
	$tableObject = new $_SESSION["current_table"]();
	$tableObject->sql_where = "{$_SESSION["detail_type"]} = '{$_SESSION["detail_id"]}'";
	$tableObject->getData();

	$tableObject->checkRelations($tableObject->data_array);
	if (empty($tableObject->errors)){
		foreach($tableObject->data_array as $todel)
		$tableObject->deleteRecord($todel);
		echo "<p align=center>Element deleted<a href=\"show.php\">";
	}
	else{
		echo "<p align=center>Delete cannot be completed. Errors found:<br>";
		foreach($tableObject->errors as $error){
			echo $error."<br>";
		}

	}
	echo "<br><a href=\"show.php\"> Back to the current table </a></p>";
}


?>