<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$i=0;

$message = "";

if(isset($_POST['update_forum'])){
	if(strlen(trim($_POST['new_forum']))){
		$new_forum = addslashes(trim($_POST['new_forum']));
		$new_description = addslashes(trim($_POST['new_description']));
		$new_order_by = (int) $_POST['new_order_by'];
		$sql = "
			update
				$tb_forums
			set
				order_by = '$new_order_by',
				forum = '$new_forum',
				description = '$new_description'
			where
				forum_id = '$_POST[forum_id]'
			";
			$query = mysql_query($sql) or die(mysql_error());
			$message = " - Forum update complete";
	} else {
			$message = " - Error, no forum submitted";
	}
}

if(isset($_POST['submit_new_forum'])){
	if(strlen(trim($_POST['new_forum']))){
		$new_forum = addslashes(trim($_POST['new_forum']));
		$new_description = addslashes(trim($_POST['new_description']));
		$new_order_by = (int) $_POST['new_order_by'];
		$new_forum_pid = isset($_POST['new_forum_pid']) ? (int) $_POST['new_forum_pid'] : 0;
		$sql = "
			insert into $tb_forums (
				forum_id,
				forum_pid,
				order_by,
				forum,
				description				
			) values (
				'',
				'$new_forum_pid',
				'$new_order_by',
				'$new_forum',
				'$new_description'
			)
		";
		$query = mysql_query($sql) or die(mysql_error());
		$message = " - New forum added";
	} else {
		$message = " - Error, no forum submitted";
	}
}

if(isset($_POST['delete_forum'])){
	delete_forum($_POST['forum_id']);
  $message = " - Forum deleted";
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
<form method="post" action="$base_url/admin/forums.php">
<tr>
<td>
<table cellpadding="6" cellspacing="0" border="0" width="100%">
<tr bgcolor="#DDDDDD">
	<td width="20%" colspan="2"><font class="clearbgtext">Order</font></td>
	<td width="20%"><font class="clearbgtext">Forum</font></td>
	<td width="20%"><font class="clearbgtext">Description</font></td>
	<td width="20%">&nbsp;</td>
	<td width="20%">&nbsp;</td>
</tr>
EOF;

$sql = "
	select
		*
	from
		$tb_forums
	where
		forum_pid = '0'
	order by
		order_by
";
$query = mysql_query($sql) or die(mysql_error());
while($array = mysql_fetch_array($query)){

// Top level existing
$table .= <<<EOF

<!-- Top level existing -->
<tr bgcolor="#EEEEEE"><form method="post" action="$base_url/admin/forums.php">
	<td colspan="2"><font class="clearbgtext"><input type="text" name="new_order_by" value="$array[order_by]" size="2"></font></td>
	<td nowrap><font class="clearbgtext"><input type="text" name="new_forum" value="$array[forum]" size="20"></font></td>
	<td><font class="clearbgtext"><input type="text" name="new_description" value="$array[description]" size="36"></font></td>
	<td><font class="clearbgtext"><input type="submit" name="update_forum" value="Update"></font></td><input type="hidden" name="forum_id" value="$array[forum_id]"></form><form method="post" action="$base_url/admin/forums.php"><input type="hidden" name="forum_id" value="$array[forum_id]">
	<td><font class="clearbgtext"><input type="submit" name="delete_forum" value="Delete"></font></td></form>
</tr>
<!-- End top level existing -->
EOF;

$s_sql = "
	select
		*
	from
		$tb_forums
	where
		forum_pid = '$array[forum_id]'
	order by
		order_by
";
$s_query = mysql_query($s_sql) or die(mysql_error());
while($s_array = mysql_fetch_array($s_query)){

// Sub level existing
$table .= <<<EOF

<!-- Sub level existing -->
<tr bgcolor="#EEEEEE"><form method="post" action="$base_url/admin/forums.php">
	<td>&nbsp;</td>	
	<td><font class="clearbgtext"><input type="text" name="new_order_by" value="$s_array[order_by]" size="2"></font></td>
	<td nowrap><font class="clearbgtext"><input type="text" name="new_forum" value="$s_array[forum]" size="20"></font></td>
	<td><font class="clearbgtext"><input type="text" name="new_description" value="$s_array[description]" size="36"></font></td>
	<td><font class="clearbgtext"><input type="submit" name="update_forum" value="Update"></font></td><input type="hidden" name="forum_id" value="$s_array[forum_id]"></form><form method="post" action="$base_url/admin/forums.php"><input type="hidden" name="forum_id" value="$s_array[forum_id]">
	<td><font class="clearbgtext"><input type="submit" name="delete_forum" value="Delete"></font></td></form>
</tr>
<!-- End sub level existing -->
EOF;

}

// Add sub level
$table .= <<<EOF

<!-- Add sub level -->
<tr bgcolor="#DDDDDD">
<form method="post" action="$base_url/admin/forums.php">
	<td>&nbsp;</td>	
	<td><font class="clearbgtext"><input type="text" name="new_order_by" size="2"></font></td>
	<td nowrap><font class="clearbgtext"><input type="text" name="new_forum" size="20"></font></td>
	<td><font class="clearbgtext"><input type="text" name="new_description" size="36"></font></td>
	<td colspan="2" align="right"><font class="clearbgtext"><input type="submit" name="submit_new_forum" value="Add Forum"></font></td><input type="hidden" name="new_forum_pid" value="$array[forum_id]"></form>
</tr>
<!-- End add sub level -->
EOF;

}

// Add top level
$table .= <<<EOF

<!-- Add top level -->
<tr bgcolor="#CCCCCC">
<form method="post" action="$base_url/admin/forums.php">
	<td colspan="2"><font class="clearbgtext"><input type="text" name="new_order_by" size="2"></font></td>
	<td nowrap><font class="clearbgtext"><input type="text" name="new_forum" size="20"></font></td>
	<td><font class="clearbgtext"><input type="text" name="new_description" size="36"></font></td>
	<td colspan="2" align="right"><font class="clearbgtext"><input type="submit" name="submit_new_forum" value="Add Forum"></font></td></form>
</tr>
<!-- End add top level -->
EOF;

$table .= <<<EOF
</table>
</td>
</form>
</tr>
</table>
EOF;

$final_output .= small_table("Forums$message", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>