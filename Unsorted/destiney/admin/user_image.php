<?php
include("config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$userid = isset($_POST['userid']) ? $_POST['userid'] : 0;
$userid = isset($_GET['userid']) ? $_GET['userid'] : $userid;

if(isset($_POST['delete_image']))
	$message = del_image($_POST['image_id']);

if(isset($_POST['upload_image'])){
	$the_file_ext_array = explode(".", $_FILES['the_file']['name']);
	$the_file_ext = $the_file_ext_array[1];
	if(isset($_POST['overwrite']))
		$message = adm_ow_image($_FILES['the_file']['tmp_name'], $the_file_ext, $userid);
	else
		$message = adm_new_image($_FILES['the_file']['tmp_name'], $the_file_ext, $userid);
}

if(isset($_POST['submit_image_url'])){
	$message = del_image($_POST['image_id']);
	$message = adm_update_url($_POST['update_where'], $_POST['new_image_url'], $userid);
}

if(isset($_POST['update_image_status'])){
	$message = adm_image_status($userid, $_POST['new_image_status']);
	$ud_sql = "
	update
		$tb_users
	set
		user_type = '$_POST[new_user_type]'
	where
		id = '$userid'
	";
	$ud_query = mysql_query($ud_sql) or die(mysql_error());
}

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edit User Image</title>
EOF;

if(isset($_POST['update_image_status'])){
$final_output .= <<<EOF
<script>
window.opener.window.document.location.reload();
window.close();
</script>
EOF;
}

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color">
EOF;

$content = "";

if(isset($message)){
$message_code = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td class="regular" align="center">$message</td>
</tr>
</table>
EOF;
}

$sql = "
	select
		user_type
	from
		$tb_users
	where
		id = '$userid'
";
$query = mysql_query($sql) or die(mysql_error());
$array = mysql_fetch_array($query);
$user_type_options = get_user_types($array["user_type"]);

$img_src = get_image($userid);

$image_code = <<<EOF
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
	<td bgcolor="black">$img_src</td>
</tr>
</table>
EOF;

$img_status_sql = "
  select
	  image_status
  from
	  $tb_users
  where
	  id = '$userid'
";
$img_status_query = mysql_query($img_status_sql) or die(mysql_error());
$img_status = mysql_result($img_status_query, 0, "image_status");

$disabled = "";
$queued = "";
$approved = "";

switch($img_status){
  case "disabled":
	  $disabled = " checked";
		break;
  case "queued":
	  $queued = " checked";
    break;
  case "approved":
	  $approved = " checked";
		break;
}

$form = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr><form method="post" action="$base_url/admin/user_image.php"><input type="hidden" name="userid" value="$userid">
<td class="regular">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="regular">User Type: <select name="new_user_type">$user_type_options</select><input type="radio" name="new_image_status" value="disabled"$disabled> Disabled <input type="radio" name="new_image_status" value="queued"$queued> Queued <input type="radio" name="new_image_status" value="approved"$approved> Approved &nbsp;</td>
<td class="regular"><input type="submit" value=" Update " name="update_image_status"></td>
</tr></table></td></form>
</tr>
<tr><form method="post" action="$base_url/admin/user_image.php" enctype="multipart/form-data">
<td class="regular">
<input type="radio" name="update_where" value="here"
EOF;

$location = query_where($userid);

if($location == "here"){
	$form .= " checked";
	$img_src = "";
}

$form .= <<<EOF
 /> Store image locally:
<br />
<input type="hidden" name="MAX_FILE_SIZE" value="$max_image_size">
<br />
File to upload:
<br />
<input type="file" name="the_file" size="30">
<br />
EOF;

if(query_image($userid)){
$form .= <<<EOF
<input type="hidden" name="overwrite" value="1" />
<input type="hidden" name="image_id" value="$userid">
<br />
EOF;
}

$form .= <<<EOF
<input type="hidden" name="show" value="upload">
<input type="submit" name="upload_image" value=" Upload Image "> 
<input type="submit" name="delete_image" value=" Delete Image ">
<br />
<br />
<input type="radio" name="update_where" value="there"
EOF;

if($location == "there")
	$form .= " checked";

$form .= <<<EOF
 /> Store image remotely:
<br />
<br />
<textarea name="new_image_url" rows="3" cols="50">$img_src</textarea>
<input type="submit" name="submit_image_url" value=" Update URL ">
</td>
<input type="hidden" name="userid" value="$userid">
</form></tr>
</table>
<br />
EOF;

$title = "Edit User Image";

if(!isset($content)) $content = "";
if(isset($userid)) $content .= $image_code;
if(!isset($message_code)) $message_code = "";
$content .= $message_code;
if(isset($userid)) $content .= $form;

$final_output .= table($title, $content);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>