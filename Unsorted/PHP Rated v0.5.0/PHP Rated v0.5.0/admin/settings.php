<?

/*
 * $Id: settings.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

$message = "";

if(isset($update_settings)){
	while(list($key, $value) = each($HTTP_POST_VARS)){
		if($key != "update_settings"){
			$sql = "
				update
					$tb_settings
				set
					setting = '$value'
				where
					name = '$key'
			";
			$query = sql_query($sql) or die(mysql_error());
		}
	}
	$message = " - Update complete";
	do_settings();
}

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
<style type="text/css">
.clearbgtext {
	font-family: $base_font;
	font-size: 13px;
	font-weight: normal;
	color: black;
	background-color: transparent;
}
</style>
<script language="javascript" type="text/javascript">if(top.location == self.location){top.location.href='index.php';}</script>
</head>
<body bgcolor="$page_bg_color">

EOF;

if(!isset($um)) $um = "";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="800">
<form method="post" action="$base_url/admin/settings.php?$sn=$sid">
<tr>
<td>
<table cellpadding="6" cellspacing="0" border="0">
EOF;

$sql = "
	select
		*
	from
		$tb_settings
	order by
		orderby
";
$query = sql_query($sql);
$i = 0;

$bool_array = array("speed_rate","site_stats","girl_t","guy_t","allow_local_image","allow_remote_image");
$cust_array = array("table_file");

while($array = sql_fetch_array($query)){
$i++;
$cell_color = "#EEEEEE";
$i % 2  ? 0 : $cell_color = "#DDDDDD";
$size = strlen($array["setting"]) + 1;
if($size > 45) $size = 45;
$table .= <<<EOF
	<tr bgcolor="$cell_color">
		<td width="75%"><font class="clearbgtext"><b>$array[name]</b><br /><span class="small">$array[descr]</span></font></td>
		<td width="25%"><font class="clearbgtext">
EOF;

if(in_array($array["name"], $bool_array)){

$table .= <<<EOF
<select name="$array[name]">
<option value="1"
EOF;

if($array["setting"] == "1") $table .= " selected";

$table .= <<<EOF
>Yes</option>
<option value="0"
EOF;

if($array["setting"] == "0") $table .= " selected";

$table .= <<<EOF
>No</option>
</select>
EOF;

} elseif(in_array($array["name"], $cust_array)) {

if($array["name"] == "table_file"){
$table_list = getTableFileList($include_path, $array["setting"]);
$table .= <<<EOF
<select name="table_file">
$table_list
</select>
EOF;
}

} else {
$table .= <<<EOF
<input type="text" name="$array[name]" value="$array[setting]" size="$size" />
EOF;
}

$table .= <<<EOF
</font></td>
</tr>

EOF;
}


$i++;
$cell_color = "#EEEEEE";
$i % 2  ? 0 : $cell_color = "#DDDDDD";
$table .= <<<EOF
<tr bgcolor="$cell_color">
<td class="regular" colspan="2" align="center"><input type="submit" name="update_settings" value=" Update Settings " /></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
EOF;

$content .= small_table("Site Settings$message", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: settings.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>