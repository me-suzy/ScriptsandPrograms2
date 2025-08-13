<?

/*
 * $Id: index.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./admin/config.php");
include("$include_path/functions.php");
include("$include_path/session.php");
include("$include_path/common.php");

$order = "";

if(isset($i)){
	$nav = convert_single($i);
	header("Location: index.php?$sn=$sid&show=view&$nav");
}


include("$include_path/$table_file");

$doc_head = template("doc_head");
eval("\$doc_head = \"$doc_head\";");

$styles = template("styles");
eval("\$styles = \"$styles\";");

$final_output = <<<FO
$doc_head
$styles
FO;

if(isset($submit_rating) && $speed_rate == 1){
$final_output .= <<<FO
<meta http-equiv="refresh" content="2; URL=$base_url/index.php?$sn=$sid&amp;show=view&amp;s=$s&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp" />
FO;
}

$body_tag = template("body_tag");
eval("\$body_tag = \"$body_tag\";");

$total_width = $left_col_width + $right_col_width + $main_col_width;

$final_output .= <<<FO
</head>
$body_tag
<table border="0" cellpadding="0" cellspacing="0" width="$total_width" align="center">
<tr>
<td width="$left_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
<tr>
<td valign="top">
FO;

include("$include_path/site_menu.php");

$final_output .= <<<FO
</td>
</tr>
FO;

if(isset($userid)){
$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/member_menu.php");

$final_output .= <<<FO
</td>
</tr>
FO;
}

$final_output .= <<<FO
<tr>
<td valign="top">
FO;

if(session_is_registered("userid")){

	$logged_in = template("logged_in");
	eval("\$logged_in = \"$logged_in\";");
	$content = $logged_in;

} else {

	$logged_out = template("logged_out");
	eval("\$logged_out = \"$logged_out\";");
	$content = $logged_out;
}

session_is_registered("userid") ? $title = "Logged In" : $title = "Login";
$final_output .= table($title, $content);
// Login Table end

$final_output .= <<<FO
</td>
</tr>
<tr>
<td valign="top">
FO;

$uo_sql = "
	select
		count(*) as count
	from
		$tb_sessions
	where
		expire > UNIX_TIMESTAMP() - 300
";
$uo_query = sql_query($uo_sql);
$uo_total = sql_result($uo_query, 0, "count") + 0;
$title = "Visitors Online";
$visitors_online = template("visitors_online");
eval("\$visitors_online = \"$visitors_online\";");
$final_output .= table($title, $visitors_online);

$final_output .= <<<FO
</td>
</tr>
</table>
</td>
<td width="$main_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

if(!isset($show)) $show = "";

switch($show){
case "tl" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/toplist.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "view" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/view.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "vc" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/view_comments.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "rate" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/rate.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;
case "view_msg" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/view_msg.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "messages" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/messages.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "pm" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/pm.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;
case "upload" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/upload.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "profile" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/profile.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "signup" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/signup.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "lost" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/lost.php");
$final_output .= <<<FO
</td>
</tr>
FO;
break;

case "comment" :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

include("$include_path/comment.php");

$final_output .= <<<FO
</td>
</tr>
FO;
break;

default :
$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

$title = $site_title;
$main_text = template("main_text");
eval("\$main_text = \"$main_text\";");
$content = $main_text;
$final_output .= table($title, $content);

$final_output .= <<<FO
</td>
</tr>
FO;
break;
}

$final_output .= <<<FO
</table>
FO;

$copyright = template("copyright");
eval("\$copyright = \"$copyright\";");

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td class="smallregular" align="center">$copyright<br />Powered By: <a class="small" href="http://destiney.com/prated/" target="_blank">pRated</a></td>
</tr>
</table>
EOF;
$final_output .= content_table($content);

$final_output .= <<<FO
</td>
<td width="$right_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

if(!isset($s)) $s = "";
$temp_s = $s;

if($girl_t == 1){

$s="f";

$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/top.php");

$final_output .= <<<FO
</td>
</tr>
FO;

}

if($guy_t == 1){

$s="m";
$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/top.php");

$final_output .= <<<FO
</td>
</tr>
FO;

}

$s = $temp_s;

if($show_site_stats == 1){

$final_output .= <<<FO
<tr>
<td valign="top">
FO;

include("$include_path/stats.php");

$final_output .= <<<FO
</td>
</tr>
FO;

}

$final_output .= <<<FO
</table>
</td>
</tr>
</table>
</body>
</html>
FO;

echo $final_output;

/*
 * $Id: index.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
