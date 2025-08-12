<?php
require("./inc/initialize.inc.php");
$tableObject = new $_SESSION["current_table"]();
$tableObject->sql_where = "{$_SESSION["detail_type"]} = '{$_SESSION["detail_id"]}'";
$tableObject->getData();

echo "<p align=center><b>Datails of {$_SESSION["detail_type"]} = {$_SESSION["detail_id"]}
			from table {$_SESSION["current_table"]}</b></p>";

echo "<table border =\"1\" width=\"100%\">";

foreach($tableObject->data_array as $row)
{
	foreach($row as $key=>$value){


		$required = '';//add * to the keys that have not to be NULL
		if($tableObject->fieldspec[$key]['required'])
			$required = '(Not null)';

		echo "<tr>";

		if($tableObject->fieldspec[$key]['pkey']){
			$keydisplay = strtoupper($key);
			echo "<td>{$keydisplay} (Primary Key): </td><td>$value
				</td>";
		}//IF
		else if($tableObject->fieldspec[$key]['type'] == 'text'){
			$keydisplay = strtoupper($key);
			echo "<td>{$keydisplay}{$required}: </td><td>"; echo nl2br($value)."
				    	</td>";
		}
		else{
			$keydisplay = strtoupper($key);
			echo "<td>{$keydisplay}{$required}: </td><td>$value
				    	</td>";

			echo "</tr>";
		}//ELSE no pkey
	}//FOREACH
}//FOREACH
echo "</td></tr>";
echo "</table>";

	?>