<?

$sql = 'CREATE TABLE ' . $_POST['dbTableName'] . ' (';

for ($i = 0;  $i < $_POST['numOfCols']; $i++) {
	$fn = "fieldName0" . $i;
	$ft = "fieldType0" . $i;
	$fe = "fieldExtras0" . $i;

	$sql .= $_POST[$fn] . " " . $_POST[$ft] . " " . $_POST[$fe] . ", ";
}

$sql = substr($sql, 0, -2) . ')';

//
//
//

$connection = mysql_connect($_POST['serverName'], $_POST['username'], $_POST['password']);
$db = mysql_select_db($_POST['dbName']);

if ($result = mysql_query($sql, $connection)) {

	$GLOBALS['display_block'] = '
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><img src="includes/images/tick.gif" border="0" alt="Table created"> Table created.</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>' . Repost();

	$GLOBALS['stage'] = "import_setup";

} else {
	$GLOBALS['display_block'] = '
		<tr>
			<td>Sorry, table creation failed:<br><br>' . mysql_error() . '</td>
		</tr>
	';
}
?>