<?php
/****************************************************************************
This file contains some functions to update an entry on the database
****************************************************************************/

/*******************************************************************
Show the update form
********************************************************************/
function showUpdate(){

	$tableObject = new $_SESSION["current_table"]();
	$tableObject->sql_where = "{$_SESSION["detail_type"]} = '{$_SESSION["detail_id"]}'";
	$tableObject->getData();

	echo "<p class=\"actionTitle\" align=center>Update form. Table {$_SESSION["current_table"]}</p>";

	showActions("update");

	echo "<table width=\"100%\">";
	echo "<form name=\"updateForm\" method=\"post\" action=\"update.php\">";

	foreach($tableObject->data_array as $row)
	{
		foreach($row as $key=>$value){
			$keydisplay = strtoupper($key);


			$required = '';//Add (Not null) to the key that has not to be null
			if($tableObject->fieldspec[$key]['required'])
			$required = '(Not null)';

			echo "<tr>";

			if($tableObject->fieldspec[$key]['pkey']){
				echo "<td>{$keydisplay} (Primary key): </td><td>{$value}<input type=\"hidden\" name=\"{$key}\" value=\"{$value}\">
				</td>";
			}//IF
			else{
				$relationship = false; //Check if the key has some relation
				// If the key has some relations build up a menu
				foreach($tableObject->relationship_out as $keyr){
					if(isset($keyr['fields'][$key])){
						foreach($keyr['fields'] as $many=>$one){

							$relationship = true;
							$tableObjectrel = new $keyr['one']();
							$tableObjectrel->sql_select = $one;
							$tableObjectrel->getData();
							echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
							foreach($tableObjectrel->data_array as $rowsel){
								foreach($rowsel as $keysel=>$valuesel){
									echo "<option value=\"$valuesel\"";
									if ($value == $valuesel)
									echo " selected ";
									echo">$valuesel</option>";
								} //FOREACH
							} //FOREACH
							echo "</select>
								</td>";
						}//FOREACH

					}//IF
				}//FOREACH

				// if the key has no relations
				if(!$relationship){
					switch ($tableObject->fieldspec[$key]['type']){
						CASE "boolean":
						echo "<td>{$keydisplay}{$required}(booleano): </td><td><select name=\"{$key}\">";
						$seltru = "";
						$selfal = "";
						if($value == $tableObject->fieldspec[$key]['true'])
						$seltru = "selected";
						else
						$selfal = "selected";

						echo "<option value=\"{$tableObject->fieldspec[$key]['true']}\" $seltru>true - {$tableObject->fieldspec[$key]['true']}</option>
							<option value=\"{$tableObject->fieldspec[$key]['false']}\" $selfal>false - {$tableObject->fieldspec[$key]['false']}</option>
							</select>
							</td>";			
						break;
						CASE "enum":
						echo "<td>{$keydisplay}{$required}: </td><td><select name=\"{$key}\">";
						foreach($tableObject->fieldspec[$key]['enum'] as $enumvalue){
							echo "<option value=\"$enumvalue\"";
							if ($value == $enumvalue)
							echo " selected ";
							echo">$enumvalue</option>";
						}
						echo "</select>
								</td>";
						break;
						CASE "date":
						echo "<td>{$keydisplay}{$required}: </td><td><input type=\"text\" name=\"{$key}\" value=\"$value\">(date DD-MM-YYYY)
							</td>";						
						break;
						CASE "time":
						echo "<td>{$keydisplay}{$required}: </td><td>(time HH:MM:SS)<input type=\"text\" name=\"{$key}\" value=\"$value\">
							</td>";						
						break;
						CASE "text":
						echo "<td>{$keydisplay}{$required}: </td><td><textarea name=\"{$key}\" wrap=\"virtual\" cols=\"50\" rows=\"10\">$value</textarea>
							</td>";	
						break;
						CASE "varchar":
						CASE "int":
						default:
						$keydisplay = strtoupper($key);
						echo "<td>{$keydisplay}{$required}: </td><td><input type=\"text\" name=\"{$key}\" value=\"$value\">
							</td>";
						break;
					} //SWITCH
				} //IF

				echo "</tr>";
			}//ELSE no pkey
		}//FOREACH
	}//FOREACH
	echo "<tr><td><input type=\"submit\" name=\"submit_update\" value=\"Update\">";
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";
}// ShowUpdate


/*******************************************************************
Check for errors. If it's all ok update the entry
*******************************************************************/
function doUpdate(){
	showActions("update");
	$tableObject = new $_SESSION["current_table"]();
	$to_update = $tableObject->std_fieldValidation_update($_SESSION['up_array']);
	if (empty($tableObject->errors)){
		$tableObject->updateRecord($to_update);
		echo "<p align=center>Element updated!<a href=\"update.php\"> Torna all'elemento</a></p>";}
		else{
			echo "<p align=center>Cannot update the element. Errors:";
			foreach($tableObject->errors as $error){
				echo "<br>".$error;
			}

			echo "<br><a href=\"update.php\">Retry</a></p>";
		}
}


?>