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
$title = "Private Messages";

if(isset($_SESSION['userid'])){

if(isset($empty_trash)){
	$return = empty_trash($_SESSION['userid']);
	if($return == true) $msg_return = "Trash has been emptied.";
}

if(isset($msg_return)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="bold" align="center">$msg_return</td>
</tr>
</table>
EOF;
}

$content .= <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Private Messages</td>
</tr>
</table>
EOF;

$content .= get_messages($_SESSION['userid'], $folder);

if($folder == "trash" && !isset($empty_trash)){

$total_trash = total_messages($_SESSION['userid'], "trash");

if($total_trash > 0){

$trash_bar = empty_trash_bar();

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular" align="center">$trash_bar</td>
</tr>
</table>
EOF;
}

}

$content .= "<br>";

}

if(!isset($_SESSION['userid'])){
$content .= <<<EOF
<br>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular" align="center">You must login to view messages.</td>
</tr>
</table>
<br>
EOF;
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