<?php
include("./config.php");
include("$include_path/common.php");

check_login();

$comment_count_message = "";

if(isset($_POST['update_comment_counts'])){
	$u = 0;
	$sql = "
		select
			user_id,
			count(user_id) as count
		from
			$tb_comments
		where
			pid = '0'
		group by
			user_id
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$c_sql = "
			update
				$tb_users
			set
				total_comments = 0,
				timestamp = timestamp
		";
		$c_query = mysql_query($c_sql) or die(mysql_error());
		while($array = mysql_fetch_array($query)){
			$u_sql = "
				update
					$tb_users
				set
					total_comments = '$array[count]',
					timestamp = timestamp
				where
					id = '$array[user_id]'
			";
			$u_query = mysql_query($u_sql) or die(mysql_error());
			$u++;
		}
	}
	$comment_count_message = "$u Comment Counts Updated<br>";
}

include("$include_path/$table_file");

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

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td>
<table cellpadding="6" cellspacing="0" border="0" width="100%">
<tr bgcolor="$cell_color"><form method="post" action="$base_url/admin/update_counts.php">
	<td><font class="clearbgtext">$comment_count_message<input type="submit" name="update_comment_counts" value="Comment Counts"></font></td>
</form></tr>
</table>
</td>
</tr>
</table>
EOF;

$final_output .= small_table("Update Counts", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>