<?

/*
 * $Id: templates.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

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
";
$query = sql_query($sql);

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0">
EOF;

$i=0;

while($array = sql_fetch_array($query)){
$cell_color = "#eeeeee";
$i % 2 ? 0: $cell_color = "#dddddd";
$i++;
$short = substr(htmlspecialchars ($array["template"]), 0, 128) . " ...";
$table .= <<<EOF
<tr bgcolor="$cell_color">
<td class="regular" align="right"><b>$array[name]:</b> </td>
<td class="regular"><a href="$base_url/admin/edit_temp.php?$sn=$sid&amp;id=$array[id]">Edit</a></td>
<td class="regular">$short</td>
</tr>
EOF;
}

$table .= <<<EOF
</table>
</td>
</tr>
</table>
EOF;

$content .= small_table("Templates", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: templates.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>