<?

/*
 * $Id: user_image.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

$styles = template("styles");
eval("\$styles = \"$styles\";");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>pRated</title>
$styles
EOF;

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color">
EOF;

$content = "";

if(isset($delete_image))
	$message = del_image($image_id);

if(isset($upload_image)){
	$the_file_ext_array = explode(".", $the_file_name);
	$the_file_ext = $the_file_ext_array[1];
	if(isset($overwrite))
		$message = adm_ow_image($the_file, $the_file_ext, $userid);
	else
		$message = adm_new_image($the_file, $the_file_ext, $userid);
}

if(isset($submit_image_url)){
	$message = del_image($image_id);
	$message = adm_update_url($update_where, $new_image_url, $userid);
}

if(isset($message)){
$message_code = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td class="regular" align="center">$message</td>
</tr>
</table>
EOF;
}

$img_src = get_image($userid);

$image_code = <<<EOF
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
	<td bgcolor="black"><img src="$img_src"></td>
</tr>
</table>
EOF;

$form = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr><form method="post" action="$base_url/admin/user_image.php?$sn=$sid" enctype="multipart/form-data">
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

if(query_image($userid) == true){
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
<input type="text" name="new_image_url" value="$img_src" size="50" />
<br />
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

/*
 * $Id: user_image.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>