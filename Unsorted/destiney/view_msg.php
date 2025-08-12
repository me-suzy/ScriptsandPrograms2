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

$folder = isset($_GET['folder']) ? $_GET['folder'] : "";

$content = "";
$title = "View Message";

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
		$message = nl2br(stripslashes($array['message']));
		$next_url = get_next_message_url($_SESSION['userid'], $folder, $array['id']);
		$prev_url = get_prev_message_url($_SESSION['userid'], $folder, $array['id']);

		$col = $folder == "inbox" ? 4 : 3;
		$folder_name = ucfirst($folder);

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> View Private Message</td>
</tr>
</table>

<table cellpadding="0" cellspacing="5" border="0" width="100%">
<tr>
	<td class="bold" width="100%">Folder: $folder_name<br><br>
	<table cellpadding="0" cellspacing="5" border="0" width="100%">
	<tr>
		<td class="bold" align="right" width="1%">Received: </td>
		<td class="regular" width="96%">$pretty_time</td>
		<td class="regular" width="1%"><a class="regular" href="$base_url/reply_msg.php?msg_id=$array[id]&amp;folder=$folder">Reply</a>&nbsp;</td>
EOF;

if($folder != "saved"){

$content .= <<<EOF
<td class="regular" width="1%"><a class="regular" href="$base_url/save_msg.php?msg_id=$array[id]&amp;folder=$folder">Save</a>&nbsp;</td>
EOF;

}

if($folder == "trash"){

$content .= <<<EOF
<td class="regular" width="1%"><a class="regular" href="$base_url/restore_msg.php?msg_id=$array[id]&amp;folder=$folder">Restore</a></td>
EOF;

} else {

$content .= <<<EOF
<td class="regular" width="1%"><a class="regular" href="$base_url/delete_msg.php?msg_id=$array[id]&amp;folder=$folder">Delete</a></td>
EOF;

}

$content .= <<<EOF
	</tr>
	<tr>
		<td class="bold" align="right">To: </td>
		<td class="regular" colspan="$col">
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
		<td class="regular" colspan="$col">
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
	<tr>
		<td class="bold" align="right">Subject: </td>
		<td class="regular" colspan="$col">$subject</td>
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
		<td class="regular">$message<br><br></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td class="regular">
	<table cellpadding="0" cellspacing="5" border="0" width="100%">
	<tr>
		<td class="regular" width="1%" nowrap="nowrap">$prev_url</td>
		<td class="regular" width="98%">&nbsp;</td>
		<td class="regular" width="1%" nowrap="nowrap">$next_url</td>
	</tr>
	</table>
	<br>
	</td>
</tr>
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