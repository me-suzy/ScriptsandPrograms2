<?

/*
 * $Id: upload.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";
$form = "";
$location = "";

if(isset($delete_image))
	$message = del_image($image_id);

if(isset($upload_image)){
	$the_file_ext_array = explode(".", $the_file_name);
	$the_file_ext = $the_file_ext_array[1];
	if(isset($overwrite))
		$message = ow_image($the_file, $the_file_ext, $userid);
	else
		$message = new_image($the_file, $the_file_ext, $userid);
}

if(isset($submit_image_url)){
	$message = del_image($image_id);
	$message = update_url($update_where, $new_image_url, $userid);
}

if(!session_is_registered("userid"))
	$message = "<br />You must login to update your image.<br /><br />";

if(isset($message)){
$message_code = <<<EOF
<table cellpadding="5" cellspacing="5" border="0" width="100%">
<tr>
<td class="regular">$message</td>
</tr>
</table>
EOF;
}

if(!isset($userid)) $userid = 0;
$img_src = get_image($userid);

$image_rules = template("image_rules");
eval("\$image_rules = \"$image_rules\";");

$image_code = <<<EOF
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
	<td bgcolor="black"><img src="$img_src"></td>
</tr>
</table>
<table cellpadding="5" cellspacing="5" border="0">
<tr>
	<td class="regular"><span class="bold">Image Rules:</span>$image_rules</td>
</tr>
<form method="post" action="$base_url/index.php?$sn=$sid" enctype="multipart/form-data">
</table>
EOF;

$form .= <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td class="regular">
EOF;

if($allow_local_image == 1){
$form .= <<<EOF
<input type="radio" name="update_where" value="here"
EOF;

$location = query_where($userid);

if($location == "here"){
	$form .= " checked";
	$img_src = "";
}

$form .= <<<EOF
 /> Store image on our servers:
<br />
<input type="hidden" name="MAX_FILE_SIZE" value="$max_image_size">
<br />
File to upload:
<br />
<input type="file" name="the_file" size="30">
EOF;

if(query_image($userid) == true){
$image_id = $userid;
$form .= <<<EOF
<input type="hidden" name="overwrite" value="1" />
<input type="hidden" name="image_id" value="$image_id">
EOF;
}

$form .= <<<EOF
<br /><br />
<input type="hidden" name="show" value="upload">
<input type="submit" name="upload_image" value=" Upload Image "> 
<input type="submit" name="delete_image" value=" Delete Image ">
<br />
EOF;
}

if($allow_remote_image == 1){
$form .= <<<EOF
<br />
<input type="hidden" name="show" value="upload">
<input type="radio" name="update_where" value="there"
EOF;

if($location == "there" or $allow_local_image == 0)
	$form .= " checked";

$form .= <<<EOF
 /> Store Image on your server:
<br />
<br />
<input type="text" name="new_image_url" value="$img_src" size="45" />
<br />
<input type="submit" name="submit_image_url" value=" Update URL ">
EOF;
}

$form .= <<<EOF
</td>
</tr>
</table>
<br />
EOF;

$form .= <<<EOF
</form>
EOF;

$title = "Upload Image";

if(session_is_registered("userid"))
	$content .= $image_code;

if(isset($message_code)) $content .= $message_code;

if(session_is_registered("userid")) $content .= $form;

$final_output .= table($title, $content);

/*
 * $Id: upload.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>