<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

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

$folder = isset($_GET['folder']) ? $_GET['folder'] : "inbox";

$content = "";
$title = "Reply To Message";

if(isset($_GET['msg_id'])){
	
	$sql = "
		select
			*
		from
			$tb_pms
		where
			user_id = '$_SESSION[userid]'
		and
			id = '$_GET[msg_id]'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$array = mysql_fetch_array($query);

		$author_name = get_username($array['author_id']);
		$pretty_time = pretty_time($array["timestamp"]);
		$subject = stripslashes($array['subject']);
		
		$message = "> " . wordwrap(stripslashes($array['message']), 70, "\n> ");

$content = <<<EOF
<br>
<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
	<td class="regular" width="100%">
	<table cellpadding="0" cellspacing="5" border="0" width="100%">
	<tr>
		<td class="bold" align="right" width="1%">Received: </td>
		<td class="regular" width="99%">$pretty_time</td>
	</tr>
	<tr>
		<td class="bold" align="right">To: </td>
		<td class="regular">
EOF;

	$check_sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$_SESSION[userid]'
	";
	$check_query = mysql_query($check_sql) or die(mysql_error());

	if(mysql_result($check_query, 0, "image_status") == "approved"){

$content .= <<<EOF
<a class="regular" href="$base_url/?i=$_SESSION[userid]">$_SESSION[username]</a>
EOF;

	} else {

		$content .= $_SESSION['username'];

	}

$content .= <<<EOF
</td>
	</tr>
	<tr>
		<td class="bold" align="right">From: </td>
		<td class="regular">
EOF;

	$check_sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$array[author_id]'
	";
	$check_query = mysql_query($check_sql) or die(mysql_error());

	if(mysql_result($check_query, 0, "image_status") == "approved"){

$content .= <<<EOF
<a class="regular" href="$base_url/?i=$array[author_id]">$author_name</a>
EOF;

	} else {

		$content .= $author_name;

	}

$content .= <<<EOF
</td>
	</tr>
	<tr><form method="post" action="$base_url/send_msg.php">
	<input type="hidden" name="receiver_id" value="$array[author_id]">
		<td class="bold" align="right">Subject: </td>
		<td class="regular"><input class="input" type="text" name="new_subject" value="RE: $subject" size="36"></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td class="regular">
	<table cellpadding="0" cellspacing="5" border="0" width="100%">
	<tr>
		<td class="bold">Message:</td>
	</tr>
	<tr>
		<td class="regular"><textarea class="input" name="new_message" rows="8" cols="72">$message</textarea></td>
	</tr>
	<tr>
		<td class="regular">&nbsp;</td>
	</tr>
	<tr>
		<td class="regular"><input class="button" type="submit" value="Send Private Message ->" name="submit_new_message"><br><br></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td class="regular"><br></td>
</form></tr>
</table>
EOF;

	} else {
		$content .= "<br><br>Sorry, that message does not exist.<br><br>";
	}

} else {
	$content .= "<br><br>Sorry, that message does not exist.<br><br>";
}


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