<?php
/****************************************************************************
This file contains some functions to insert an entry on the database
****************************************************************************/

/*******************************************************************
Show the element that we're going to insert
********************************************************************/
function showInsert(){

	$tableObject = new $_SESSION["current_table"]();

	echo "<p class=\"actionTitle\" align=center>Insert an element on the table {$_SESSION["current_table"]}</p>";

	showActions("insert");

	echo "<table width=\"100%\">";
	echo "<form name=\"insertForm\" method=\"post\" action=\"insert.php\">";

	foreach($tableObject->fieldspec as $key=>$value)
	{

		$keydisplay = strtoupper($key); // Show the keys in lowercase
		$required = '';//Add * to the keys that hasn't to be null
		if($tableObject->fieldspec[$key]['required'])
		$required = '(Not null)';

		echo "<tr>";

		if($value['pkey']){
			if($value['auto_increment']){
				echo "<td>{$keydisplay} (Primary key): </td><td>Unique autoincremental
				</td>";	
			}
			else{

				echo "<td>{$keydisplay} (Primary key): </td><td><input type=\"text\" name=\"{$key}\" value=\"\">
				</td>";
			}
		}//IF
		else{
			$relationship = false; //To check if the key has some relation

			// If the key has a relation build up a menu
			foreach($tableObject->relationship_out as $keyr){
				if(isset($keyr['fields'][$key])){
					foreach($keyr['fields'] as $many=>$one){

						$relationship = true;
						$tableObjectrel = new $keyr['one']();
						$tableObjectrel->sql_select = $one;
						$tableObjectrel->getData();
						$keydisplay = strtoupper($key);
						echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
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

			// If the key has no relation
			if(!$relationship){
				$keydisplay = strtoupper($key);
				switch ($tableObject->fieldspec[$key]['type']){
					CASE "boolean":
					echo "<td>{$keydisplay}{$required}(booleano): </td><td><select name=\"{$key}\">";
					echo "<option value=\"{$tableObject->fieldspec[$key]['true']}\">true - {$tableObject->fieldspec[$key]['true']}</option>
							<option value=\"{$tableObject->fieldspec[$key]['false']}\">false - {$tableObject->fieldspec[$key]['false']}</option>
							</select>
							</td>";					
					break;
					CASE "enum":
					echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
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
					CASE "text":
					echo "<td>{$keydisplay}{$required}: </td><td><textarea cols=\"50\" rows=\"10\" name=\"{$key}\" wrap=\"virtual\"></textarea>
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
	echo "<tr><td><input type=\"submit\" name=\"submit_insert\" value=\"Insert\">";
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";
}// ShowUpdate


/*******************************************************************
Check if there are errors on the element to insert. If it's all ok insert the element
*******************************************************************/
function doInsert(){
	showActions("insert");
	$tableObject = new $_SESSION["current_table"]();
	$to_insert = $tableObject->std_fieldValidation_insert($_SESSION['ins_array']);
	if (empty($tableObject->errors)){
		$tableObject->insertRecord($to_insert);
		echo "<p align=center>Element added!<a href=\"insert.php\"> Add a new element</a></p>";}
		else{
			echo "<p align=center>Element cannot be added. Errors:";
			foreach($tableObject->errors as $error){
				echo "<br>".$error;
			}

			echo "<br><a href=\"insert.php\">Retry</a></p>";
		}
}


?>