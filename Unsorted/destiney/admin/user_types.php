<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$i=0;

$message = "";

if(isset($_POST['update_user_type'])){
	if(strlen(trim($_POST['new_user_type']))){
		$new_user_type = trim($_POST['new_user_type']);
		$new_order_by = (int) $_POST['new_order_by'];
		$sql = "
			update
				$tb_user_types
			set
				user_type = '$new_user_type',
				gender = '$_POST[new_gender]',
				order_by = '$new_order_by'
			where
				id = '$_POST[user_type_id]'
			";
			$query = mysql_query($sql) or die(mysql_error());
			$message = " - Update complete";
	} else {
			$message = " - Error, no user type submitted";
	}
}

if(isset($_POST['submit_new_user_type'])){
	if(strlen(trim($_POST['new_user_type']))){
		$new_user_type = trim($_POST['new_user_type']);
		$new_order_by = (int) $_POST['new_order_by'];
		$sql = "
			insert into $tb_user_types (
				id,
				user_type,
				gender,
				order_by
			) values (
				'',
				'$new_user_type',
				'$_POST[new_gender]',
				'$new_order_by'
			)
		";
		$query = mysql_query($sql) or die(mysql_error());
		$message = " - New user type added";
	} else {
		$message = " - Error, no user type submitted";
	}
}

if(isset($_POST['delete_user_type'])){
	$sql = "
		delete from
			$tb_user_types
		where
			id = '$_POST[user_type_id]'
  ";
	$query = mysql_query($sql) or die(mysql_error());
  $message = " - User type deleted";
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
<form method="post" action="$base_url/admin/user_types.php">
<tr>
<td>
<table cellpadding="6" cellspacing="0" border="0" width="100%">
<tr bgcolor="#DDDDDD">
	<td width="20%"><font class="clearbgtext">Type</font></td>
	<td width="20%"><font class="clearbgtext">Gender</font></td>
	<td width="20%"><font class="clearbgtext">Order</font></td>
	<td width="20%">&nbsp;</td>
	<td width="20%">&nbsp;</td>
</tr>
EOF;

$sql = "
	select
		*
	from
		$tb_user_types
	order by
		order_by
";
$query = mysql_query($sql) or die(mysql_error());
while($array = mysql_fetch_array($query)){

$f_slected = $m_slected = "";

if($array["gender"] == "m"){
	$m_slected = " checked";
}

if($array["gender"] == "f"){
	$f_slected = " checked";
}

$i++;
$cell_color = "#EEEEEE";
$i % 2  ? 0 : $cell_color = "#DDDDDD";
$table .= <<<EOF
<tr bgcolor="$cell_color"><form method="post" action="$base_url/admin/user_types.php">
	<td><font class="clearbgtext"><input type="text" name="new_user_type" value="$array[user_type]"></font></td>
	<td nowrap><font class="clearbgtext"><input type="radio" name="new_gender" value="m"$m_slected> M <input type="radio" name="new_gender" value="f"$f_slected> F</font></td>
	<td><font class="clearbgtext"><input type="text" name="new_order_by" value="$array[order_by]" size="2"></font></td>
	<td><font class="clearbgtext"><input type="submit" name="update_user_type" value="Update"></font></td><input type="hidden" name="user_type_id" value="$array[id]"></form><form method="post" action="$base_url/admin/user_types.php"><input type="hidden" name="user_type_id" value="$array[id]">
	<td><font class="clearbgtext"><input type="submit" name="delete_user_type" value="Delete"></font></td></form>
</tr>
EOF;
}

$table .= <<<EOF
<tr bgcolor="$cell_color">
<form method="post" action="$base_url/admin/user_types.php">
	<td><font class="clearbgtext"><input type="text" name="new_user_type"></font></td>
	<td nowrap><font class="clearbgtext"><input type="radio" name="new_gender" value="m"> M <input type="radio" name="new_gender" value="f"> F</font></td>
	<td><font class="clearbgtext"><input type="text" name="new_order_by" value="$array[order_by]" size="2"></font></td>
	<td colspan="2"><font class="clearbgtext"><input type="submit" name="submit_new_user_type" value="Add New Type"></font></td></form>
</tr>
EOF;

$table .= <<<EOF
</table>
</td>
</form>
</tr>
</table>
EOF;

$final_output .= small_table("User Types$message", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>