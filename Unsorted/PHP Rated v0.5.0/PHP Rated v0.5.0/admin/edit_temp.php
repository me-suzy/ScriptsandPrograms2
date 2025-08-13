<?

/*
 * $Id: edit_temp.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

if(isset($update_template)){

$ud_sql = "
	update
		$tb_templates
	set
		template = '$new_template'
	where
		id = '$id'
";

if($ud_query = sql_query($ud_sql))
	$update_message = "Update Complete";
else
	$update_message = "Update Failed";

$um = <<<EOF
<tr>
<td class="regular" colspan="2" align="center"><span class="bold">$update_message</span> - <a href="$base_url/admin/templates.php?$sn=$sid">Click to Continue</a></td>
</tr>
EOF;
}

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
<script language="javascript" type="text/javascript">if(top.location == self.location){top.location.href='index.php';}</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$sql = "
	select
		*
	from
		$tb_templates
	where
		id = '$id'
";

$query = sql_query($sql);

if(!isset($um)) $um = "";

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
<form method="post" action="$base_url/admin/edit_temp.php?$sn=$sid">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0">$um
EOF;

if($array = sql_fetch_array($query)){
$table .= <<<EOF
<tr>
<td class="regular" align="right">$array[name]:</td>
<td class="regular"><textarea name="new_template" rows="16" cols="80">$array[template]</textarea></td>
</tr>
EOF;
}

$table .= <<<EOF
<tr>
<td class="regular" align="center" colspan="2"><input type="submit" name="update_template" value=" Update Template " /></td>
</tr>
</table>
</td>
</tr>
<input type="hidden" name="id" value="$array[id]" />
</form>
</table>
EOF;

$content .= small_table("Templates", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: edit_temp.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>