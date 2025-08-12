<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$i=0;

$message = "";

if(isset($_POST['update_image_type'])){
	$sql = "
		update
			$tb_image_types
		set
			ext = '$_POST[new_image_type]'
		where
			id = '$_POST[image_type_id]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$message = " - Update complete";
}

if(isset($_POST['submit_new_image_type'])){
	if(strlen(trim($_POST['new_image_type']))){
		$new_image_type = trim($_POST['new_image_type']);
		$sql = "
			insert into $tb_image_types (
				id,
				ext
			) values (
				'',
				'$new_image_type'
			)
		";
		$query = mysql_query($sql) or die(mysql_error());
		$message = " - New image type added";
	} else {
		$message = " - Error, no image type submitted";
	}
}

if(isset($_POST['delete_image_type'])){
	$sql = "
		delete from
			$tb_image_types
		where
			id = '$_POST[image_type_id]'
  ";
  $query = mysql_query($sql) or die(mysql_error());
  $message = " - Image type deleted";
}

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color">

EOF;

if(!isset($um)) $um = "";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<form method="post" action="$base_url/admin/image_types.php">
<tr>
<td>
<table cellpadding="6" cellspacing="0" border="0" width="100%">
EOF;

$sql = "
	select
		*
	from
		$tb_image_types
	order by
		ext
";
$query = mysql_query($sql) or die(mysql_error());
while($array = mysql_fetch_array($query)){
$i++;
$cell_color = "#EEEEEE";
$i % 2  ? 0 : $cell_color = "#DDDDDD";
$table .= <<<EOF
<tr bgcolor="$cell_color"><form method="post" action="$base_url/admin/image_types.php">
	<td width="33%"><font class="clearbgtext"><input type="text" name="new_image_type" value="$array[ext]" size="5"></font></td>
	<td width="33%"><font class="clearbgtext"><input type="submit" name="update_image_type" value="Update"></font></td><input type="hidden" name="image_type_id" value="$array[id]"></form><form method="post" action="$base_url/admin/image_types.php"><input type="hidden" name="image_type_id" value="$array[id]">
	<td width="33%"><font class="clearbgtext"><input type="submit" name="delete_image_type" value="Delete"></font></td></form>
</tr>
EOF;
}

$table .= <<<EOF
<tr bgcolor="$cell_color"><form method="post" action="$base_url/admin/image_types.php">
	<td width="33%"><font class="clearbgtext"><input type="text" name="new_image_type" size="5"></font></td>
	<td width="67%" colspan="2"><font class="clearbgtext"><input type="submit" name="submit_new_image_type" value="Add New Type"></font></td></form>
</tr>
EOF;

$table .= <<<EOF
</table>
</td>
</form>
</tr>
</table>
EOF;

$final_output .= small_table("Image Types$message", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>