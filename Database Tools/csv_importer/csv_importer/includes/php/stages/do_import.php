<?

// ProcessCsvFile($fileName, $dbConnection, $dbTableName, $dbFieldNames, $primaryKeyField, $primaryKeyIndex, $action, $all, $operand, $compareFrom, $compareTo)

$connection = mysql_connect($_POST['serverName'], $_POST['username'], $_POST['password']) or die(mysql_error());
$db = mysql_select_db($_POST['dbName'], $connection);


// Create import rule file (if name is set)
if ($_POST['ruleSetName'] != "") CreateImportRuleFile($_POST['ruleSetName'], $_POST['ruleConfig']);

$log_spacer = "<br><hr><br>";

// Make primary key if req'd:
if (isset($_POST['chkSetPrimaryKey'])) {
	$sql = "ALTER TABLE " . $_POST['dbTableName'] . " ADD PRIMARY KEY (" . $_POST['cmbPrimaryKeyField'] . ")";
	$log .= ($result = mysql_query($sql, $connection)) ? "Added primary key to: " . $_POST['cmbPrimaryKeyField'] : "Primary key was NOT added: " . mysql_error();
	$log .= "<br>" . $log_spacer;
} else if ($_POST['txtNewField']) {
	$sql = "ALTER TABLE " . $_POST['dbTableName'] . " ADD " . $_POST['txtNewField'] . " INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
	$log .= ($result = mysql_query($sql, $connection)) ? "Created primary key field \"" . $_POST['txtNewField'] . "\"" : "Primary key was NOT created: " . mysql_error();
	$log .=  "<br>" . $log_spacer;
}

$dbFieldNames = explode("|", $_POST['hiddenFieldNames']);

for ($i = 0; $i < count($dbFieldNames); $i++) {
	$tempVar = "colConfig" . $i;
	$colConfig[count($colConfig)] = $_POST[$tempVar];
	
	$tempVar = "setValue" . $i;
	$setValues[count($setValues)] = ($_POST[$tempVar]) ? $_POST[$tempVar] : "";
}

for ($i = 0; $i < $_POST['numOfRules']; $i++) {
	$tempFields[$i] = array();
	for ($z = 0; $z < $GLOBALS['ruleStages']; $z++) {
		if ($z == 5) {
			array_push($tempFields[$i], "txtRule" . $i . "_step" . ($z+1));
		} else {
			array_push($tempFields[$i], "cmbRule" . $i . "_step" . ($z+1));
		}
	}
	
	$action = $_POST[$tempFields[$i][0]];
	$all = ($_POST[$tempFields[$i][1]] == "all") ? 1 : 0;
	$operand = $_POST[$tempFields[$i][3]];
	$compareFrom = $_POST[$tempFields[$i][2]];
	$compareTo = ($_POST[$tempFields[$i][4]] == "[set value]") ? $_POST[$tempFields[$i][5]] : $_POST[$tempFields[$i][4]];

	switch ($_POST[$tempFields[$i][1]]) {
		case "all" :
			switch ($_POST[$tempFields[$i][0]]) {
				case "insert" :
				case "update" :
					$result = ProcessCsvFile($_POST['fileDirectory'] . $_POST['csvFile'], $connection,
								$_POST['dbTableName'], $dbFieldNames, $colConfig, $setValues, $_POST['primaryKeyField'], $_POST['primaryKeyIndex'],
								$action, 1, $operand, $compareFrom, $compareTo, $_POST['startAtLine'], $_POST['loggingOptions']);
					
					$log .= "<strong>Rule " . ($i+1). ":</strong><br>";
					$log .= $result['completed'] . " records ok.<br>";
					$log .= $result['failed'] . " records failed.<br>";
					$log .= "<br>" . $result['log'] . "<br>";
					$log .= $log_spacer;
				break;
				
				case "delete" :
					$sql = "DELETE FROM " . $_POST['dbTableName'] . " WHERE 1 > 0";
					$result = mysql_query($sql, $connection) or die(mysql_error() . '<br><br>' . $sql);
					
					$log .= "<strong>Rule " . ($i+1). ":</strong><br>";
					$log .= mysql_affected_rows($connection) . " records deleted.<br>";
					$log .= $log_spacer;
				break;
			}
		break;
		
		case "where" :
			$result = ProcessCsvFile($_POST['fileDirectory'] . $_POST['csvFile'], $connection,
						$_POST['dbTableName'], $dbFieldNames, $colConfig, $setValues, $_POST['primaryKeyField'], $_POST['primaryKeyIndex'],
						$action, 0, $operand, $compareFrom, $compareTo, $_POST['startAtLine'], $_POST['loggingOptions']);
						
			$log .= "<strong>Rule " . ($i+1). ":</strong><br>" . $result['completed'] . " ";
			$log .= ($_POST[$tempFields[$i][0]] == "insert") ? "inserted" : $_POST[$tempFields[$i][0]] . "d";
			$log .= " records successfully.<br>";
			$log .= $result['failed'] . " records failed.<br>";
			$log .= "<br>" . $result['log'] . "<br>";
			$log .= $log_spacer;
		break;
	}
	
}

$GLOBALS['display_block'] = substr($log, 0, -strlen($log_spacer));
?>