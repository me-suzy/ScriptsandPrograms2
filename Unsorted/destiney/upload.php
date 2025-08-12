<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

$img_src = "";

include("$include_path/$table_file");

if(isset($_POST['delete_image'])){
	
	$sql = "
		select
			concat(id, '.', image_ext) as image
		from
			$tb_users
		where
			id = '$_SESSION[userid]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	
	$file = $image_path . "/" . mysql_result($query, 0, "image");
	
	if(file_exists($file)){
		
		if(unlink($file)){
			drop_rotation($_SESSION['userid']);
			set_notfound_image($_SESSION['userid']);
			$message = "Your image has been deleted.";
		} else {
			$message = "An error occured, your image was not deleted.";
		}

	} else {
		$message = "Could not delete image, no image was found.";
	}
}

if(isset($_POST['upload_image'])){

	$upload_image_error = false;
	$allowed = false;

	$the_file_ext_array = explode(".", $_FILES['the_file']['name']);
	$the_file_ext = $the_file_ext_array[sizeof($the_file_ext_array)-1];

	if(!strlen($_FILES['the_file']['name'])) {
		
		$upload_image_error = true;
		$message = "You did not upload anything!<br><br>";
		
	}

	$sql = "
		select
			*
		from
			$tb_image_types
	";
	$query = mysql_query($sql) or die(mysql_error());
	
	while($array = mysql_fetch_array($query))
		if($the_file_ext == $array["ext"]) $allowed = true;
	
	if(!$allowed){

		$upload_image_error = true;
		
		$message = "The file that you uploaded was of a type that is not<br>allowed, you are only allowed to upload files of the type:<br><br>";

		$ssql = "
			select
				*
			from
				$tb_image_types
		";
		$squery = mysql_query($ssql) or die(mysql_error());
		while($sarray = mysql_fetch_array($squery))
				$message .= "." . $sarray["ext"] . "<br>";

	}
	
	if($allowed){
		
		$size = getimagesize($_FILES['the_file']['tmp_name']);
		list($foo, $width, $bar, $height) = explode("\"", $size[3]);
		
		if($width > $max_image_width){
			
			$upload_image_error = true;
			
			$message = "Your image should be no wider than " . $max_image_width . " Pixels<br><br>";
		}
		
		if($height > $max_image_height){

			$upload_image_error = true;

			$message = "Your image should be no higher than " . $max_image_height . " Pixels<br><br>";
		}

	}

	if(!$upload_image_error){

		$file_name = $_SESSION['userid'] . "." . $the_file_ext;

		if(isset($_POST['overwrite'])){

			if(@copy($_FILES['the_file']['tmp_name'], $image_path . "/" . $file_name)) {
				
				update_ext($the_file_ext, $_SESSION['userid']);
				
				$message = "Your image was uploaded successfully.  It will now have to be reviewed<br>before being shown live on the site.  Please allow up to 48 hours for review.";

			} else {

				$message = "Your image was not uploaded, a file write error occured.";	
			}

			update_url("here", "", $_SESSION['userid']);
			queue_image($_SESSION['userid']);

		} else {
			
			if (!@copy($_FILES['the_file']['tmp_name'], $image_path . "/" . $file_name)){
				
				$message = "Your new image was not uploaded.";	
			
			} else {
				
				$message = "Your new image has been uploaded.";
				update_ext($the_file_ext, $_SESSION['userid']);
			
			}
			
			update_url("here", "", $_SESSION['userid']);
			queue_image($_SESSION['userid']);

		}
	}
}

if(isset($_POST['submit_image_url'])){
	$message = del_image($_SESSION[userid]);
	$message = update_url($_POST['update_where'], $_POST['new_image_url'], $_SESSION['userid']);

}

include("$include_path/doc_head.php");
include("$include_path/styles.php");

$final_output .= <<<FO
</head>
<body bgcolor="$page_bg_color">
<table border="0" cellpadding="0" cellspacing="0" width="$total_width" align="center">
<tr>
	<td colspan="3" width="100%" valign="bottom">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="middle" class="dc">$title_image</td>
		<td align="right" valign="bottom">
FO;

include("$include_path/logged_status.php");

$final_output .= <<<FO
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
<td width="$left_col_width" valign="top">
FO;

include("$include_path/left.php");

$final_output .= <<<FO
</td>
<td width="$main_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Upload Image</td>
</tr>
</table>
EOF;

$form = "";
$location = "";

if(isset($message)){
$message_code = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
<td class="regular">$message</td>
</tr>
</table>
EOF;
}

$userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

$image_src = get_image($userid);

include("$include_path/image_rules.php");
$image_code = $image_rules;

$image_code .= <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
	<td class="regular">$image_src</td>
</tr>
</table>
<form method="post" action="$base_url/upload.php" enctype="multipart/form-data">
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

if($location == "here" || !$allow_remote_image){
	$form .= " checked";
	$img_src = "";
}

$form .= <<<EOF
> Store image at $site_title:
<br>
<input type="hidden" name="MAX_FILE_SIZE" value="$max_image_size">
<br>
File to upload:
<br>
<input class="input" type="file" name="the_file" size="30">
<input type="hidden" name="overwrite" value="1">
<br><br>
<input type="hidden" name="show" value="upload">
<input class="button" type="submit" name="upload_image" value=" Upload Image "> 
<input class="button" type="submit" name="delete_image" value=" Delete Image ">
<br>
EOF;
}

if($allow_remote_image == 1){
$form .= <<<EOF
<br>
<input type="hidden" name="show" value="upload">
<input type="radio" name="update_where" value="there"
EOF;

if($location == "there" or $allow_local_image == 0)
	$form .= " checked";

$the_img_src = strstr($img_src, "notfound_image") ? "" : $img_src;

$form .= <<<EOF
> Store Image on you own remote server:
<br>
<br>
<input class="input" type="text" name="new_image_url" value="$the_img_src" size="45">
<br>
<input class="button" type="submit" name="submit_image_url" value=" Update URL ">
EOF;
}

$form .= <<<EOF
</td>
</tr>
</table>
<br>
EOF;

$form .= <<<EOF
</form>
EOF;

$title = "Upload Image";

if(isset($_SESSION['userid']))
	$content .= $image_code;

if(isset($message_code)) $content .= $message_code;

if(isset($_SESSION['userid'])) $content .= $form;

$final_output .= table($title, $content);

$final_output .= <<<FO
</td>
</tr>
FO;

$final_output .= <<<FO
</table>
FO;

include("$include_path/copyright.php");

$final_output .= <<<FO
</td>
<td width="$right_col_width" valign="top">
FO;

include("$include_path/right.php");

$final_output .= <<<FO
</td>
</tr>
</table>
</body>
</html>
FO;

$final_output = final_output($final_output);

echo $final_output;

?>