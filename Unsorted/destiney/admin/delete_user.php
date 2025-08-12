<?php
include("config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

if(isset($_POST['delete_user']) && isset($_POST['userid']) && isset($_POST['sure'])){ 
  
	$d_sql = "
	  delete from
      $tb_users
	  where
	    id = '$_POST[userid]'
  ";
  $d_query = mysql_query($d_sql) or die(mysql_error());
  
	$dc_sql = "
	  delete from
      $tb_comments
	  where
	    user_id = '$_POST[userid]'
  ";
  $dc_query = mysql_query($dc_sql) or die(mysql_error());

	$dac_sql = "
	  delete from
      $tb_comments
	  where
	    author_id = '$_POST[userid]'
  ";
  $dac_query = mysql_query($dac_sql) or die(mysql_error());
  
	$dr_sql = "
	  delete from
      $tb_ratings
	  where
	    user_id = '$_POST[userid]'
  ";
  $dr_query = mysql_query($dr_sql) or die(mysql_error());

  $delete_success = true;
}

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Delete User</title>
EOF;

if(isset($delete_success) && $delete_success){
$final_output .= <<<EOF
<script>window.opener.window.document.location.reload();</script>
EOF;
}

if((isset($delete_success) && $delete_success) || isset($_POST['not_sure'])){
$final_output .= <<<EOF
<script>window.close();</script>
EOF;
}

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0">
EOF;

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
EOF;

if(isset($dm)) $table .= $dm;

$userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
$userid = isset($_GET['userid']) ? $_GET['userid'] : $userid;

$table .= <<<EOF
<tr>
<td class="regular" align="center" colspan="2">Are you sure?</td>
</tr>
<tr><form method="post" action="$base_url/admin/delete_user.php"><input type="hidden" name="delete_user" value="1"><input type="hidden" name="userid" value="$userid">
<td class="regular" align="right"><input type="submit" name="sure" value="Yes"></td>
</form><form method="post" action="$base_url/admin/delete_user.php">
<td class="regular"><input type="submit" name="not_sure" value="No"></td></form>
</tr>
</table>
</td>
</form></tr>
</table>
EOF;

$final_output .= small_table("Delete User", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>