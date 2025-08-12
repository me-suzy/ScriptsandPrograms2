<?
################################
# MySQL Variables - Must be configured first!
$sqlhost = "";
$sqllogin = "";
$sqlpass = "";
$sqldb = "";
$tablecats = "scriptcats";
$tablescripts = "scripts";
################################


$sitetitle = "NukedWeb";
$main_site_url = "http://www.nukedweb.com/";
$script_index_url = "http://www.nukedweb.com/php/";
$adminemail = "root@localhost";
$emailnotify = "1";
$max_search_results = "30";
$adminpass = "theadmin";

$headerfile = "";
$footerfile = "";
$table_width = "98%";
$table_border = "1";
$cellspacing = "3";
$cellpadding = "2";
$table_head_color = "#507ca0";
$table_head_textcolor = "#FFFFFF";
$table_bgcolor = "#EEEEEE";
$table_border_color = "#000000";
$table_textcolor = "#000000";
$fontname = "Verdana, Arial, Helvetica, sans-serif";


$cat_link_color = "#000000";
$empty_cat_link_color = "#777777";

$db = mysql_connect($sqlhost, $sqllogin, $sqlpass) or die("OOps!");
mysql_select_db($sqldb, $db);

function getcategoriesascombo($selectcat){
	global $tablecats;
	$sql = "select * from $tablecats";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);

	for ($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$id = $resrow[0];
		$cat = $resrow[1];
		$selected = "";
		if ($id==$selectcat) $selected = " selected";
		$cmbcats .= "<option value='$id'$selected>$cat</option>\n";
	}
	return $cmbcats;
}

?>