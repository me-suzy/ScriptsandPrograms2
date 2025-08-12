<?php

require("./inc/initialize.inc.php");

$tableObject = new $_SESSION["current_table"]();
$tableObject->sql_orderby = $_SESSION["order_by"];
$where = $tableObject->searchRecord($_SESSION['search_array']);

if($where == '')
$where = "No parameters selected, search will be done on the entire table {$_SESSION['current_table']}";
else
$where = $where." from table {$_SESSION['current_table']}";


echo "<p align=center><b>Results from table {$_SESSION["current_table"]}";
echo "with parameters: $where</b></p>";


foreach($tableObject->data_array as $row)
{
	echo "<table width=\"100%\" border = 1>";
	foreach($row as $key=>$value){


		$required = '';//add * to the keys that have not to be NULL
		if($tableObject->fieldspec[$key]['required'])
		$required = '(Not null)';

		echo "<tr>";

		if($tableObject->fieldspec[$key]['pkey']){
			$keydisplay = strtoupper($key);
			echo "<td>{$keydisplay} (Primary key): </td><td>$value
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
	echo "</td></tr>";
	echo "</table><br>";
}//FOREACH


	?>