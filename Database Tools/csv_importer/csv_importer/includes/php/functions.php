<?

/*
	includes/php/functions.php

	As with all of the files, don't mess about with stuff as you might stop things working!
*/



function BuildSQL($dbTable, $action, $fieldNames, $fieldValues, $where) {
	if (count($fieldNames) != count($fieldValues)) return;
	
	switch (strtolower($action)) {
		case "insert" :
			for ($i = 0; $i < count($fieldNames); $i++) {
				$sqlFieldNames .= $fieldNames[$i] . ', ';
				$sqlFieldValues .= '"' . $fieldValues[$i] . '", ';
			}
			return "INSERT INTO $dbTable (" . substr($sqlFieldNames, 0, -2) . ") VALUES (" . substr($sqlFieldValues, 0, -2) . ")";
		break;
			
		case "update" :
			for ($i = 0; $i < count($fieldNames); $i++) {
				$sqlFields .= $fieldNames[$i] . ' = "' . $fieldValues[$i] . '", ';
			}
			return "UPDATE $dbTable SET " . substr($sqlFields, 0, -2) . " WHERE " . $where;
		break;
			
		case "delete" :
			return "DELETE FROM $dbTable WHERE " . $where;
		break;
	}
}


function CreateForm($caller, $stage) {
	switch($stage) {
		case "create_table" :
			// JavaScript
			$js_files = Array("none");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		case "db_connect" :
			// JavaScript
			$js_files = Array("db_connect");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		case "do_import" :
			// JavaScript
			$js_files = Array("none");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		case "import_setup" :
			// JavaScript
			$js_files = Array("elements", "gen_functions", "import_rules", "import_setup_db");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		case "preview" :
			// JavaScript
			$js_files = Array("preview");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		case "select_db" :
			// JavaScript
			$js_files = Array("select_db", "elements", "gen_functions");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
		break;
		
		default :
			// JavaScript
			$js_files = Array("select_file");
				$GLOBALS['js_scripts'] = IncludeJavaScript($js_files) . "\n\n";
	}
	
	if ($stage != "") {
		include("includes/php/stages/" . $stage . ".php");
	} else {
		include("includes/php/stages/select_file.php");
	}
}


function CreateImportRuleFile($irfName, $irfConfig) {
	$fileString = "";
	$irfFileName = ereg_replace("[^[:alnum:]_]", "", $irfName) . ".irf";
	$irfConfig = explode("|", $irfConfig);
	for ($i = 0; $i < count($irfConfig); $i++) {
		if (ereg("^_r[0-9]$", $irfConfig[$i])) {
			$fileString = substr($fileString, 0, -2);
			$fileString .= "), " . ereg_replace("^_r", "", $irfConfig[$i]) . " => array(";
		} else {
			$fileString .= "\"" . $irfConfig[$i] . "\", ";
		}
	}
	
	$fileString = "<?\n\$config = array(\"name\" => array(\"$irfName\"),\n\t\t\"settings\" => array(" . substr($fileString, 3, -2) . ")));\n?>";
	
	if (!$fp = @fopen("import_rules/" . $irfFileName, "w+")) {
		$log = "Could not create file.&nbsp;&nbsp;Check rights on server.";
		return $log;
	}

	$log = (fwrite($fp, $fileString)) ? "Import rule file created and configuration written." : "Opened file but config could not be written.";
	fclose($fp);
	
	return $log;
}


function FileList($caller, $fieldName, $fieldSize, $fileDirectory) {
	if ($fileDirectory == "upLevels_root") {
		$fileDirectory = "/";
	} else if (ereg("upLevels_[0-9]", $fileDirectory)) {
		$upLevels = intval(str_replace("upLevels_", "", $fileDirectory));
		$fileDirectory = str_repeat("../", $upLevels);
	} else if (ereg("^dir_", $fileDirectory)) {
		$upLevels =  (substr_count($fileDirectory, "../")-1);
		$fileDirectory = substr($fileDirectory, 4) . "/";
	} else {
		$fileDirectory = "my_csv_files/";
	}
	
	if ($dir = @opendir($fileDirectory)) {
		$options = "";
		while ((($file = readdir($dir)) !== false)) {
	 		switch($file) {
				case "." :
					$options .= "\n\t" . '<option value="upLevels_root">[' . $file . ']</option>';
				break;
				
				case ".." :
					$options .= "\n\t" . '<option value="upLevels_' . ($upLevels+1) . '">[' . $file . ']</option>';
				break;
				
				default :
					if (eregi("^\.{1,2}$|\.(csv|txt|dat)$", $file)) {
						$file_options .= "\n\t<option value=\"$file\" class=\"spaz\">$file<options>";
					} else if (@is_dir($fileDirectory . $file)) {
						$dir_options .= "\n\t<option value=\"dir_$fileDirectory$file\" class=\"spaz\">[$file]<options>";
					}
			}
		}
		closedir($dir);
		
		$fileList['browsing'] = "\nBrowsing: <strong>$fileDirectory</strong>";
		$fileList['fileDirectory'] = $fileDirectory;
		$fileList['options'] = "<select name=\"" . $fieldName . '" size="' . $fieldSize . "\">" . $options . $dir_options . $file_options . "\n</select>";
		
		return $fileList;
	} else {
		return "File search disabled.&nbsp;&nbsp;Do you have suitable rights for reading directories?";
	}
}


function GetFirstRow($csvFile, $delimiter) {
	$firstRow['php'] = Array();
	$line = 0;

	if (!$myFile = @fopen(urldecode($csvFile), "r")) die("Can't re-open .CSV file: " . $csvFile);
	
	while (($line < 1) && ($data = fgetcsv($myFile, 1024, $delimiter))) {
		$numOfCols = count($data);
		
		for ($i = 0; $i < $numOfCols; $i++) {
			$firstRow['js'] .= '"' . $data[$i] . '", ';
			array_push($firstRow['php'], $data[$i]);
		}
		
		$line++;

	}
	
	fclose($myFile);
	
	$firstRow['js'] = "\n\nvar firstRowArray = new Array(" . substr($firstRow['js']	, 0, -2) . ");";
	return $firstRow;
}


function GetImportRuleFiles() {
	// open directory
	if ($myFile = opendir("import_rules")) {
		$return['js_names'] .= "\n" . 'var irfNameArray = new Array();';
		$return['js_configs'] .= "\n\n" . 'var irfConfigArray = new Array();';

	    while (false !== ($file = readdir($myFile))) { 
			if (eregi("\.irf$", $file)) {
				include("import_rules/" . $file);
				$handle = substr($file, 0, -4);
			
				$return['js_configs'] .= "\n" . 'irfConfigArray["' . $handle . '"] = new Array();';
				$return['js_names'] .= "\n\t" . 'irfNameArray["' . $handle . '"] = "' . $config['name'][0] . '";';
				$return['selectOptions'] .= "\n\t\t" . '<option value="' . $handle . '">' . $config['name'][0] . '</option>';
				
				for ($i = 0; $i < count($config['settings']); $i++) {
				
					$return['js_configs'] .= "\n\t" . 'irfConfigArray["' . $handle . '"][' . $i . '] = new Array();';
					for ($z = 0; $z < count($config['settings'][$i]); $z++) {
						$return['js_configs'] .= (is_int($config['settings'][$i][$z])) ?
							"\n\t\t" . 'irfConfigArray["' . $handle . '"][' . $i . '][' . $z . '] = ' . $config['settings'][$i][$z] . ';' :
							"\n\t\t" . 'irfConfigArray["' . $handle . '"][' . $i . '][' . $z . '] = "' . $config['settings'][$i][$z] . '";' ;
					}
	
				}
			}
	    }

		// close file link
	    closedir($myFile); 
	}
	
	return $return;
}


function GetOperands($language) {
	switch(strtolower($language)) {
		case "javascript" :
		case "js" :
			$return = "\nvar operandsArray = new Array()";
			for ($i = 0; $i < count($GLOBALS['operandsArray']); $i++) {
				$return .= "\n\toperandsArray[operandsArray.length] = new Array(\"" . $GLOBALS['operandsArray'][$i][0] . "\", \"" . $GLOBALS['operandsArray'][$i][1] . "\");";
			}
			return $return;
		break;
	}
}


function GetPreview($csvFile, $delimiter, $previewLimit, $useFRAH) {
	$getPreview['previewData'] = '<table cellspacing="1" cellpadding="2" border="0" class="grid">';
	$line = 0;
	
	if (!$myFile = @fopen(urldecode($csvFile), "r")) die("Can't re-open .CSV file: " . $csvFile);
	
	while (($line < $previewLimit) && ($data = fgetcsv($myFile, 1024, $delimiter))) {
		$getPreview['numOfCols'] = count($data);
		
		if ($line == 0) {
			$getPreview['previewData'] .= '<tr align="center">';
			for ($i = 0; $i < $getPreview['numOfCols']; $i++) {
				$getPreview['previewData'] .= "\n\t\t<td>Col " . ($i+1) . "</td>";
			}
			$getPreview['previewData'] .= "</tr>";
			$getPreview['previewData'] .= ($useFRAH != "") ? "\n\t<tr id=\"headerRow\" class=\"header\">" : "\n\t<tr id=\"headerRow\">";
		} else {
			$getPreview['previewData'] .= "\n\t<tr>";
		}

		for ($i = 0; $i < $getPreview['numOfCols']; $i++) {
			$getPreview['previewData'] .= (strlen($data[$i]) > 20) ? "\n\t\t<td>" . stripslashes(substr($data[$i], 0, 20)) . "...</td>" : "\n\t\t<td>" . stripslashes($data[$i]) . "</td>";
		}
		
		$getPreview['previewData'] .= "\n\t</tr>";
		
		$line++;

	}
	
	fclose($myFile);
	
	$getPreview['previewData'] .= "</table>";
	
	return $getPreview;
}




function IncludeJavaScript($files) {
	for ($i = 0; $i < count($files); $i++) {
		$return .= "\n" . '<script language="JavaScript" src="includes/javascript/' . $files[$i] . '.js"></script>';
	}
	return $return;
}


// BuildSQL($dbTable, $action, $fieldNames, $fieldValues, $where) {

function ProcessCsvFile($fileName,
								$dbConnection, $dbTableName, $dbFieldNames, $colConfig, $setValues,
								$primaryKeyField, $primaryKeyIndex,
								$action, $all, $operand, $compareFrom, $compareTo,
								$startAtLine, $loggingOptions) {
	if (!$myFile = @fopen($fileName, "r")) die("Can't re-open .CSV file: " . $fileName);

	$completedRecords = 0;
	$failedRecords = 0;
	$line = 0;
	$removedFields = array();

	for ($i = 0; $i < count($colConfig); $i++) {
		switch ($colConfig[$i]) {
			case -2 :		// None
				$removedFields[count($removedFields)] = $i;
				unset($dbFieldNames[$i]);
				unset($setValues[$i]);
			break;
		}
	}

	$dbFieldNames = array_values($dbFieldNames);
	$setValues = array_values($setValues);

	while ($data = fgetcsv($myFile, 1024, $_POST['delimiter'])) {
		if ($line >= $startAtLine) {
			$numOfCols = count($data);
			$primaryKeyValue = $data[$primaryKeyIndex];
			$tempVar = $data[intval(str_replace("_colOption", "", $compareFrom))];
			
			for ($i = 0; $i < count($removedFields); $i++) {
				unset($data[$removedFields[$i]]);
			}
			$data = array_values($data);
			
			for ($i = 0; $i < count($setValues); $i++) {
				if ($setValues[$i] != "") $data[$i] = $setValues[$i];
			}

			if ($all == 0) {
				switch($operand) {
					case "contains" :
						$doIt = (ereg($compareTo, $tempVar)) ? 1 : 0;
					break;
					
					case "endswith" :
						$doIt = (ereg($compareTo . "$", $tempVar)) ? 1 : 0;
					break;
					
					case "greaterthan" :
						$doIt = ($tempVar > $compareTo) ? 1 : 0;
					break;
					
					case "empty" :
						$doIt = ($tempVar == "") ? 1 : 0;
					break;
					
					case "equalto" :
						$doIt = ($tempVar == $compareTo) ? 1 : 0;
					break;
					
					case "lessthan" :
						$doIt = ($tempVar < $compareTo) ? 1 : 0;
					break;
					
					case "notempty" :
						$doIt = ($tempVar != "") ? 1 : 0;
					break;
					
					case "notequalto" :
						$doIt = ($tempVar != $compareTo) ? 1 : 0;
					break;
					
					case "startswith" :
						$doIt = (ereg("^" . $compareTo, $tempVar)) ? 1 : 0;
					break;
				}
			}
			
			if (($doIt == 1) || ($all == 1)) {
				switch ($action) {
					case "insert" :
						$sql = BuildSQL($dbTableName, $action, $dbFieldNames, $data, "");
					break;
				
					case "update" :
					case "delete" :
						$sql = BuildSQL($dbTableName, $action, $dbFieldNames, $data, $primaryKeyField . " = \"" . $primaryKeyValue . "\"");
					break;
				}
				
				if ($result = mysql_query($sql, $dbConnection)) {
					$completedRecords++;
					if ($loggingOptions == "all") $log .= "<li>Record \"" . $data[$primaryKeyIndex] . "\" succeeded.&nbsp;&nbsp;[line " . ($line+1) . "]";
				} else {
					$failedRecords++;
					if ($loggingOptions != "summary") $log .= "<li class=\"failed\">Record \"" . $data[$primaryKeyIndex] . "\" failed: " . mysql_error() . " [line " . ($line+1) . "]";
				}
			}

		}  // End "if ($line >= $startAtLine) {"
		
		$line++;

	}  // End "while [fgetcsv]..."

	fclose($myFile);
	
	$return['completed'] = $completedRecords;
	$return['log'] = $log;
	$return['failed'] = $failedRecords;
	
	return $return;
}


function Repost() {
	$repostArray = Array("delimiter", "csvFile", "useFRAH", "serverName", "username", "password", "dbName", "dbTableName");
	
	while (list($name, $value) = each($_POST)) {
		if (in_array($name, $repostArray)) $return .= "\n\t" . '<input type="hidden" name="' . $name . '" value="' . $value .'">';
	}
	
	return $return;
}



function SaveConnection($serverName, $username, $password) {
	if (($serverName != "") || ($username != "") || ($password != "")) return "checked";
}

?>