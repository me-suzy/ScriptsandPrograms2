<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

if(!isset($_GET['i']) && !isset($_POST['submit'])){
	header("Location: $base_url/");
	exit();
}

$return_message = "";
$picture_error = "";
$email_error = "";
$error = false;

if(!isset($_SESSION['ps'])){
	$_SESSION['ps'][] = array();
}

if(isset($_GET['i'])){
	$i = $_GET['i'];
} else if(isset($_POST['i'])){
	$i = $_POST['i'];
}

$image_src = get_image($i);
$user_name = get_username($i);

if(isset($_POST['submit'])){

	if(eregi("^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+(\.[a-zA-Z0-9_-])+", $_POST['friend_email'])){
		
		if(in_array(($i . $_POST['friend_email']), $_SESSION['ps'])){
			$error = true;
			$picture_error = "<br><br>You already sent this picture to " . $_POST['friend_email'] . ".";
		}

	} else {
		$error = true;
		$email_error = "<br>Your friend's email address must be valid";
	}

	$image_src = get_image($i);

	$email = get_email($_SESSION['userid']);
	$headers = "From: " . $_SESSION['username'] . " <" . $email . ">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	$subject = "A Picture from a Friend";

$message = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>A Picture from a Friend</title>
</head>
<body bgcolor="$page_bg_color" link="$base_link_color" vlink="$base_link_color" alink="$base_link_color">
<br>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td><b><font size="2" face="$base_font" color="$base_font_color">&nbsp;<a href="$base_url/"><b>$site_title</b></a> >> A Picture from a Friend</font></b></td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td><br>
<table cellpadding="5" cellspacing="0" border="0">
<tr>
<td><font size="2" face="$base_font" color="$base_font_color">Your friend $_SESSION[username]  <a href="mailto:$email">&lt;$email&gt;</a> sent you this picture:</font></td>
</tr>
<tr>
<td>
<table cellpadding="0" cellspacing="5" border="0">
<tr>
<td>$image_src</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><font size="2" face="$base_font" color="$base_font_color">You can see more pictures like this one, and even upload your own picture at <a href="$base_url/" target="_blank">$site_title</a></font></td>
</tr>
</table>
<br><br><br><br>
</td>
</tr>
</table>
</body>
</html>
EOF;

	if(!$error){
		if(mail($_POST['friend_email'], $subject, $message, $headers)){
			$_SESSION['ps'][] = ($i . $_POST['friend_email']);
			$return_message = "Message Sent.";
		} else {
			$return_message = "Message NOT sent, mail() returned FALSE..  check your setup.";
		}
	} else {
		$return_message = "An error occured.";
	}

}

include("$include_path/$table_file");
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

$friend_email = $error ? $_POST['friend_email'] : "";

$html = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Send To Friend</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
	<form method="post" action="$base_url/send_to_friend.php">
	<input type="hidden" name="i" value="$i">
	<tr>
	<td class="regular">
EOF;

$userid = $i;
include("$include_path/vote_bar.php");
$html .= $vote_bar;	

$html .= <<<EOF
	<table cellpadding="5" cellspacing="0" border="0">
	<tr>
		<td class="regular">$image_src<span class="error">$picture_error</span></td>
	</tr>
	<tr>
		<td class="regular">
EOF;

include("$include_path/profile_bar.php");
$html .= $profile_bar;

$html .= <<<EOF
		</td>
	</tr>
	<tr>
		<td class="regular">&nbsp;</td>
	</tr>
	<tr>
		<td class="regular"><span class="error">$return_message</span>&nbsp;</td>
	</tr>
	<tr>
		<td class="regular">Enter your friend's email address to send them a copy of this image:</td>
	</tr>
	<tr>
		<td class="regular"><input class="input" type="text" name="friend_email" size="32" value="$friend_email"> <input class="button" type="submit" name="submit" value="Send Now ->"><span class="error">$email_error</span></td>
	</tr>
	</table>
	<br><br><br><br>
	</td>
</form></tr>
<tr>
<td class="regular"></td>
</tr>
</table>
EOF;

$final_output .= table("Send To Friend", $html);

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