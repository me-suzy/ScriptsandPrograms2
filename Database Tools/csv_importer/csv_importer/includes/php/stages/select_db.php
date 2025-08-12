<?

$GLOBALS['stage'] = "";

if (!$connection = @mysql_connect($_POST['serverName'], $_POST['username'], $_POST['password'])) {

	$GLOBALS['instructions'] = "Connection to MySQL <strong>failed</strong>.&nbsp; Below is the description of the error:";
	$GLOBALS['display_block'] = "<tr class=\"sectionHeader\"><td>" . mysql_error() . "</td></tr>";
	
	switch (mysql_errno()) {
		case 1045 :
			$GLOBALS['display_block'] .= "<tr><td><br>It looks like you have entered your username and/or password incorrectly.&nbsp; Check them and try again.<br><br>Your MySQL username and password may be different from your FTP password.<br><br>If you continue to experience problems, contact your network administrator/server hosts.</td></tr>";
		break;
		
		case 2005 :
			$GLOBALS['display_block'] .= "<tr><td><br>It looks like you have entered an incorrect server name.<br><br>If you are working on a remote server, try the domain name: e.g. \"www.mydomain.com\".&nbsp; You might like to try \"localhost\".</td></tr>";
		break;
		
		default :
		
	}
} else {
	// Save cookie if ticked (re-new each time)
	if (isset($_POST['saveConnection'])) {
	
		setcookie ("ck_csv[serverName]", $_POST['serverName'], time()+31536000);
		setcookie ("ck_csv[username]", $_POST['username'], time()+31536000);
		setcookie ("ck_csv[password]", $_POST['password'], time()+31536000);
		
	// Remove if not
	} else {
	
		setcookie ("ck_csv[serverName]", $serverName, time()-31536000);
		setcookie ("ck_csv[username]", $username, time()-31536000);
		setcookie ("ck_csv[password]", $password, time()-31536000);
	
	}
	
	
	// Get databases and tables
	$sql = "SHOW DATABASES";
	$result = mysql_query($sql, $connection) or die(mysql_error() . '<br><br>' . $sql);
	
	if (mysql_num_rows($result) > 0) {
		$existingOption = '<input type="radio" name="entryMethod" value="existing" onClick="CreateForm_existing()"> An <strong>existing</strong> table.<br>';
		
		$js = '
		var mysqlDatabaseArray = new Array();
		var mysqlTableArray = new Array();
		';
		
		while ($row = mysql_fetch_row($result)) {
			mysql_select_db($row[0], $connection);
			$js_databases .= "\nmysqlDatabaseArray[mysqlDatabaseArray.length] = \"" . $row[0] . "\";";
			$js_tables .= "\n\nmysqlTableArray['" . $row[0] . "'] = new Array();";
			
			$s_sql = 'SHOW TABLES';
			$s_result = mysql_query($s_sql, $connection);
			
			while ($s_row = mysql_fetch_row($s_result)) {
				$js_tables .= "\nmysqlTableArray['" . $row[0] . "'][mysqlTableArray['" . $row[0] . "'].length] = \"" . $s_row[0] . "\";";
			}
		}
	}
	
	
	// Get header columns for preview
	$line = 0;
	if (!$myFile = @fopen(urldecode($_POST['fileDirectory'] . $_POST['csvFile']), "r")) die("Can't re-open .CSV file: " . urldecode($_POST['csvFile']));

	$previewLimit = 4;

	while (($line < $previewLimit) && ($data = fgetcsv($myFile, 1024, $_POST['delimiter']))) {
		$numOfCols = count($data);
		$js_dataPreview .= "\ndataPreviewArray[$line] = new Array();";

		for ($index = 0; $index < $numOfCols; $index++) {
			$js_dataPreview .= "\ndataPreviewArray[$line][dataPreviewArray[$line].length] = \"" . stripslashes($data[$index]) . "\";";
		}
		
		$line++;
	}

	fclose($myFile);
	
	$js_dataPreview = "\n\nvar numOfCols = " . $numOfCols . ";\nvar dataPreviewArray = new Array();\n" . $js_dataPreview;
	
	
	//
	//
	//
	
	
	$GLOBALS['instructions'] = "Connection to MySQL succeeded.&nbsp; To where would you like to import your csv file?";
	$GLOBALS['display_block'] = '
		<tr class="sectionHeader">
			<td>
				' . $existingOption . '
				<input type="radio" name="entryMethod" value="new" onClick="CreateForm_new()"> A <strong>new</strong> table.
			</td>
		</tr>
		<tr class="spacer">
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><div id="workarea"> </div></td>
		</tr>
			<input type="hidden" name="numOfCols" value="' . $numOfCols . '">' . Repost();

	$GLOBALS['js_block'] = $js . $js_databases . $js_tables . $js_dataPreview . "\n\n";
	
	mysql_close($connection);
}

?>