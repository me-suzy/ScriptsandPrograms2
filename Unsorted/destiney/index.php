<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");

if(isset($_GET['v'])) convert_single($_GET['v']);

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

if(isset($_GET['s'])){
	if(($_GET['s'] > 0 && $_GET['s'] < 9) || $_GET['s'] == "m" || $_GET['s'] == "f"){
		$_SESSION['show'] = $_GET['s'];
		header("Location: $base_url/");
		exit();
	}
	if($_GET['s'] == -1){
		unset($_SESSION['show']);
		header("Location: $base_url/");
		exit();
	}
}

$and = "";

if(isset($_SESSION['show'])){

	switch($_SESSION['show']){
		case "m":
			$and = "and ( " . get_gender_types_sql("m") . ")";
			break;
		case "f":
			$and = "and ( " . get_gender_types_sql("f") . ")";
			break;
		default :
			$and = "and user_type = " . $_SESSION['show'];
	}
}

$sql = "
	select
		id
	from
		$tb_users
	where
		image_status = 'approved'
	$and
";
$query = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($query)){

	$hide = array();
	$id_array = array();

	if(isset($_SESSION['ra']) && sizeof($_SESSION['ra'])){
		$ra = substr($_SESSION['ra'], 0, strlen($_SESSION['ra'])-1);
		$hide = explode(",", $ra);
	}

	while($array = mysql_fetch_array($query)){
		if(!in_array($array["id"], $hide)){
			$id_array[] = $array["id"];
		}
	}

	if(sizeof($id_array) < 1){
		unset($_SESSION['ra']);
		header("Location: $base_url/");
		exit();
	}

	$rand = mt_rand(0, sizeof($id_array)-1);
	$rid = @$id_array[$rand];

	$rate_sql = "
		select
			id,
			username as user_name
		from
			$tb_users
		where
	";

	if(isset($_GET['i'])){
		$rate_sql .= "
				id = '$_GET[i]'
		";
	} else {
		$rate_sql .= "
				id = '$rid'
		";
	}
	$rate_query = mysql_query($rate_sql) or die(mysql_error());
	$rate_array = mysql_fetch_array($rate_query);

	$title = $rate_array["user_name"];
	$i = $rate_array["id"];

	$image_src = get_image($i);

	include("$include_path/vote_bar.php");

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $title</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>$vote_bar</td><form method="post" action="$base_url/">
	<td class="regular" align="right" valign="bottom">Show: <select class="input" onChange="window.open(this.options[this.selectedIndex].value, '_top')" name="only_show"><option value="$base_url/?s=-1">Show All</option>
EOF;

$sh = isset($_SESSION['show']) ? $_SESSION['show'] : "";
$content .= get_submit_user_types($sh);

$content .= <<<EOF
</select>&nbsp;</td></form>
</tr>
</table>
<table cellpadding="5" cellspacing="5" border="0" width="100%">
<tr>
	<td class="regular">$image_src</td>
</tr>
<tr>
	<td class="regular">
EOF;

include("$include_path/profile_bar.php");
$content .= $profile_bar;

$content .= <<<EOF
	</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>$vote_bar</td><form method="post" action="$base_url/">
	<td class="regular" align="right" valign="bottom">Show: <select class="input" onChange="window.open(this.options[this.selectedIndex].value, '_top')" name="only_show"><option value="$base_url/?s=-1">Show All</option>
EOF;

$sh = isset($_SESSION['show']) ? $_SESSION['show'] : "";
$content .= get_submit_user_types($sh);

$content .= <<<EOF
</select>&nbsp;</td></form>
</tr>
</table>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>
	<table cellpadding="5" cellspacing="5" border="0">
	<tr>
		<td>
EOF;

if($show_graphs){

include("$include_path/stats_table.php");
$content .= $stats_table;

}

$content .= <<<EOF
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<br><br><br></td>
EOF;

$final_output .= table($title, $content);

}

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