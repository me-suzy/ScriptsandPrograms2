<?

// Get preview
$preview = GetPreview($_POST['csvFile'], $_POST['delimiter'], 4, $_POST['useFRAH']);

if ($_POST['useFRAH'] == "") {
	for ($i = 0; $i < $preview['numOfCols']; $i++) {
		$colOptions .= '<option value="' . ($i+1) . '">Column ' . ($i+1) . '</option>';  // For combo boxes in the DB fields
	}
} else {
	$firstRow = GetFirstRow($_POST['csvFile'], $_POST['delimiter']);

	for ($i = 0; $i < count($firstRow['php']); $i++) {
		$colOptions .= '<option value="col_' . $i . '">' . ucwords($firstRow['php'][$i]) . '</option>';  // For combo boxes in the DB fields
	}
}

$irfs = GetImportRuleFiles();

//
//
//

$connection = mysql_connect($_POST['serverName'], $_POST['username'], $_POST['password']);
$db = mysql_select_db($_POST['dbName'], $connection);

$s_sql = 'EXPLAIN ' . $_POST['dbTableName'];
$s_result = mysql_query($s_sql, $connection);
$i = 0;

$fieldOptions = '
	<tr class="sectionHeader">
		<td>In table <strong><em>' . $_POST['dbName'] . '</em></strong>...</td>
	</tr>
	<tr class="list">
		<td colspan=5><a href="JavaScript:Sequence(document.form1)">Click here</a> to sequence your columns.&nbsp;&nbsp;This will match database field 1 with column 1, field 2 with column 2 and so on.</td>
	</tr>';

while ($s_row = mysql_fetch_array($s_result)) {

	$fieldNames .= '<option value="' . $s_row['Field'] . '">' . $s_row['Field'] . '</option>';
	$hiddenFieldNames .= $s_row['Field'] . "|";

	if ($s_row['Key'] == "PRI") {
		$primaryKeyImage = '<img src="includes/images/primary_key.gif" alt="Primary Key">';
		$primaryKeyField = $s_row['Field'];
		$primaryKeyIndex = $i;
	}

	$fieldOptions .= '
	<tr>
		<td>
			<table cellspacing=0 cellpadding=0 border=0 width="100%">
				<tr>
					<td width="2%">' . $primaryKeyImage . '&nbsp;</td>
					<td width="18%"><strong>' . $s_row['Field'] . '</strong></td>
					<td width="18%">should receive</td>
					<td width="20%">
						<select name="dbFieldName[' . $i . ']" onChange="DbFieldOnChange(this.form, this, ' . $i . ')">
							<option value="">Select column</option>
							<option value="[none]">[No value]</option>
							<option value="[setvalue]">[Set value]</option>
							' . $colOptions . '
						</select>
						<input type="hidden" name="colConfig' . $i . '" value="">
					</td>
					<td width="54%"><div id="dbFieldDiv[' . $i . ']">&nbsp;</div></td>
				</tr>
				<tr class="underline">
					<td colspan=5>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>';

	$i++;
	$primaryKeyImage = "";
}

if ($primaryKeyField == "") {
	$GLOBALS['display_block'] = '
		<tr class="alert">
			<td><strong>Warning!</strong><br><br>There is no primary key defined on this table.&nbsp; It is strongly advised that you define one.&nbsp; If you do not, you can only insert records into the database.&nbsp; You will not be able to delete or update.
	<br><br>
		Which field would you like to use as your primary key?

		<select id="cmbPrimaryKeyField" name="cmbPrimaryKeyField" onChange="SetPrimaryKey(this, \'alterTable\')">
			<option value="">Select field</option>
			<option value="newfield">[Add new field]</option>
			<option value="insertonly">[Insert only]</option>
			' . $fieldNames . '
		</select>
		<div id="alterTable">&nbsp;</div>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	';
}

$GLOBALS['display_block'] .= '
	<tr>
		<td align="center">' . $preview['previewData'] . '</td>
	</tr>
	<tr>
		<td>
			<br>
			<table cellpadding=2 cellspacing=1 border="0" width="100%">
				' . $fieldOptions . '
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr class="sectionHeader">
		<td><strong>Import rules</strong> allow you to add, delete and update columns based on the contents of one or more columns.</td>
	</tr>
	<tr class="list">
		<td align="right"><a href="JavaScript:NewRule()">Add new rule</a> | <a href="JavaScript:SaveRuleSet()">Save rule set</a> | 
			<select name="cmbLoadRuleSet" onChange="UseImportRuleSet(this)">
				<option value="">Load rule set</option>
				' . $irfs['selectOptions'] . '
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<div id="importRuleSetWorkarea"></div>
		</td>
	</tr>
	<tr class="underline">
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr class="sectionHeader">
		<td><strong>Other options</strong></td>
	</tr>
	<tr class="noBorder">
		<td class="underline"><em>Would you like to import the first line of the CSV file</em>?
			<br>
			&nbsp;&nbsp;<input type="radio" name="startAtLine" value="0"' . (($_POST['useFRAH'] == "") ? ' checked' : '') . '>Yes
			<br>
			&nbsp;&nbsp;<input type="radio" name="startAtLine" value="1"' . (($_POST['useFRAH'] == "") ? '' : ' checked') . '>No</td>
	</tr>
	<tr class="noBorder">
		<td class="underline"><em>Logging options</em>:
			<br>
			&nbsp;&nbsp;<input type="radio" name="loggingOptions" value="summary">View summary only.
			<br>
			&nbsp;&nbsp;<input type="radio" name="loggingOptions" value="errors" checked>View summary and errors. (Recommended)
			<br>
			&nbsp;&nbsp;<input type="radio" name="loggingOptions" value="all">View summary and all database activity.</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<input id="hiddenFieldNames" type="hidden" name="hiddenFieldNames" value="' . substr($hiddenFieldNames, 0, -1) . '">
	<input id="numOfRules" type="hidden" name="numOfRules" value="">
	<input id="primaryKeyField" type="hidden" name="primaryKeyField" value="' . $primaryKeyField . '">
	<input id="primaryKeyIndex" type="hidden" name="primaryKeyIndex" value="' . $primaryKeyIndex . '">
	<input id="ruleConfig" type="hidden" name="ruleConfig" value="">
	<input id="ruleSetName" type="hidden" name="ruleSetName" value="">
	' . Repost();

$GLOBALS['instructions'] = "Now it's time to specify how your data is imported.&nbsp; First of all you need to specify which field in your database should receive which column from your CSV file.<br><br>Further on you will specify how your data is imported via rules.&nbsp; Again, a small preview of your CSV file is here as a quick reference to help you.";

$GLOBALS['js_block'] = '
var dbName = "' . $_POST['dbName'] . '";
var dbTableName = "' . $_POST['dbTableName'] . '";
var numOfCols = ' . $preview['numOfCols'] . ';' . 
GetOperands('js') . '
var ruleStages = ' . $GLOBALS['ruleStages'] . ';
' . $firstRow['js'] . '
' . $irfs['js_names'] . $irfs['js_configs'];

$GLOBALS['stage'] = "do_import";
$GLOBALS['tableWidth'] = "100%";

/*
	<tr class="sectionHeader">
		<td align="right"><a href="JavaScript:NewRule()">Add new rule</a> | <a href="JavaScript:SaveRuleSet()">Save rule set</a>&nbsp;&nbsp;&nbsp;
			<select name="cmbLoadRuleSet" onChange="UseImportRuleSet(this)">
				<option value="">Load rule set</option>
				' . $irfs['selectOptions'] . '
			</select>
		</td>
	</tr>
*/
?>