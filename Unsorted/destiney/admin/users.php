<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
<script>
var ProfilePopUpX = (screen.width/2)-275;
var ProfilePopUpY = (screen.height/2)-300;
var ProfilePos = "left="+ProfilePopUpX+",top="+ProfilePopUpY;
function ProfilePopUp(link){
ProfilePopUpWindow = window.open(link,"Profile","scrollbars=yes,resizable=yes,width=550,height=600,"+ProfilePos);
}
var ImagePopUpX = (screen.width/2)-320;
var ImagePopUpY = (screen.height/2)-240;
var ImagePos = "left="+ImagePopUpX+",top="+ImagePopUpY;
function ImagePopUp(link){
ImagePopUpWindow = window.open(link,"Image","scrollbars=yes,resizable=yes,width=640,height=480,"+ImagePos);
}
var DeletePopUpX = (screen.width/2)-65;
var DeletePopUpY = (screen.height/2)-36;
var DeletePos = "left="+DeletePopUpX+",top="+DeletePopUpY;
function DeletePopUp(link){
DeletePopUpWindow = window.open(link,"Delete","scrollbars=no,resizable=no,width=130,height=72,"+DeletePos);
}
</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
EOF;

if(isset($_GET['sort']))
	$sort = $_GET['sort'];
else
	$sort = "";

switch ($sort){
	case "image_status_disabled" : $order_by = $tb_users . ".image_status"; break;
	case "image_status_new" : $order_by = $tb_users . ".image_status desc"; break;
	case "id_asc" : $order_by = $tb_users . ".id"; break;
	case "id_desc" : $order_by = $tb_users . ".id desc"; break;
	case "username_asc" : $order_by = "username"; break;
	case "username_desc" : $order_by = "username desc"; break;
	case "country_asc" : $order_by = "country"; break;
	case "country_desc" : $order_by = "country desc"; break;
	case "comment_count_asc" : $order_by = "comment_count"; break;
	case "comment_count_desc" : $order_by = "comment_count desc"; break;
	case "realname_asc" : $order_by = "realname"; break;
	case "realname_desc" : $order_by = "realname desc"; break;
	case "age_asc" : $order_by = "age"; break;
	case "age_desc" : $order_by = "age desc"; break;
	case "user_type_asc" : $order_by = "user_type"; break;
	case "user_type_desc" : $order_by = "user_type desc"; break;
	case "total_ratings_asc" : $order_by = "total_ratings"; break;
	case "total_ratings_desc" : $order_by = "total_ratings desc"; break;
	case "total_points_asc" : $order_by = "total_points"; break;
	case "total_points_desc" : $order_by = "total_points desc"; break;
	case "avg_rating_asc" : $order_by = "average_rating"; break;
	case "avg_rating_desc" : $order_by = "average_rating desc"; break;
	default : $order_by = $tb_users . ".id desc"; break;
}

$c_sql = "
  select
	  count(*) as count
  from
	  $tb_users
";
$c_query = mysql_query($c_sql) or die(mysql_error());
$total_members = mysql_result($c_query, 0, "count");

$nav_url = "$base_url/admin/users.php?sort=$sort&amp;";
$nav = nav_links($total_members, $admin_users_pp, $admin_users_np, $cp, $nav_url);

$table .= <<<EOF
<tr>
<td class="regular" colspan="13">$nav</td>
</tr>
EOF;

$sql = "
	select
		$tb_users.id as id,
		$tb_users.username as username,
		$tb_users.realname as realname,
		$tb_users.email as email,
		$tb_users.age as age,
		$tb_users.user_type as user_type,
		$tb_users.total_ratings as total_ratings,
		$tb_users.total_points as total_points,
		$tb_users.average_rating as average_rating,
		$tb_users.country as country,
		$tb_users.image_status as image_status,
		count($tb_comments.user_id) as comment_count
	from
		$tb_users
	left join
		$tb_comments
	on
		$tb_users.id = $tb_comments.user_id
	group by
		$tb_users.id
	order by
		$order_by
  limit
    $sr, $admin_users_pp
";

$query = mysql_query($sql) or die(mysql_error());

$table .= <<<EOF
<tr>
	<td><a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == $tb_users . ".id")
	$table .= "id_desc";
else
	$table .= "id_asc";

$table .= <<<EOF
">ID</a>&nbsp;</td>
	<td><a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "username")
	$table .= "username_desc";
else
	$table .= "username_asc";

$table .= <<<EOF
">Username</a>&nbsp;</td>
<td align="right">&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "age")
	$table .= "age_desc";
else
	$table .= "age_asc";

$table .= <<<EOF
">Age</a></td>
<td>&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "user_type")
	$table .= "user_type_desc";
else
	$table .= "user_type_asc";

$table .= <<<EOF
">User Type</a></td>
<td align="right">&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "total_ratings")
	$table .= "total_ratings_desc";
else
	$table .= "total_ratings_asc";

$table .= <<<EOF
">Ratings</a></td>
<td align="right">&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "total_points")
	$table .= "total_points_desc";
else
	$table .= "total_points_asc";

$table .= <<<EOF
">Points</a></td>
<td align="right">&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "average_rating")
	$table .= "avg_rating_desc";
else
	$table .= "avg_rating_asc";

$table .= <<<EOF
">Avg</a></td>
<td>&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "comment_count")
	$table .= "comment_count_desc";
else
	$table .= "comment_count_asc";

$table .= <<<EOF
">Com</a></td>
<td>&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == "country")
	$table .= "country_desc";
else
	$table .= "country_asc";

$table .= <<<EOF
">Country</a></td>
<td>&nbsp;<a class="bold" href="$base_url/admin/users.php?sort=
EOF;

if($order_by == $tb_users . ".image_status desc")
	$table .= "image_status_disabled";
else
	$table .= "image_status_new";

$table .= <<<EOF
">Status</a></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
</tr>
EOF;

$cc=0;

while($array = mysql_fetch_array($query)){

if($array["country"] == ""){$array["country"] = "None.gif";}
$short_entry = eregi_replace(".gif", "", $array["country"]);
$country = eregi_replace("_", " ", $short_entry);

$cc++;
$cell = "#eeeeee";
$cc % 2  ? 0 : $cell = "#dddddd";

switch($array["image_status"]){
	case "approved":
$status = <<<EOF
<font color="blue">Approved</font>
EOF;
		break;
	case "queued":
$status = <<<EOF
<font color="green">Queued</font>
EOF;
		break;
	case "disabled":
$status = <<<EOF
<font color="red">Disabled</font>
EOF;
}

$user_type = get_user_type($array["user_type"]) . "&nbsp;";

$table .= <<<EOF
<tr bgcolor="$cell">
<td class="regular">$array[id]</td>
<td class="regular"><a href="mailto:$array[realname] <$array[email]>">$array[username]</a></td>
<td class="regular" align="right">$array[age]</td>
<td class="regular">$user_type</td>
<td class="regular" align="right">$array[total_ratings]</td>
<td class="regular" align="right">$array[total_points]</td>
<td class="regular" align="right">&nbsp;$array[average_rating]</td>
<td class="regular" align="right">&nbsp;$array[comment_count]</td>
<td class="regular" align="center"><img align="top" border="1" height="13" width="20" src="$base_url/images/flags/$array[country]" hspace="5" alt="$country" title="$country" /></td>
<td class="regular">$status</td>
<td class="regular"><a href="javascript:ProfilePopUp('$base_url/admin/user_profile.php?id=$array[id]');">Profile</a></td>
<td class="regular"><a href="javascript:ImagePopUp('$base_url/admin/user_image.php?userid=$array[id]');">Image</a></td>
<td class="regular"><a href="javascript:DeletePopUp('$base_url/admin/delete_user.php?userid=$array[id]');">X</a></td>
</tr>
EOF;

}

$table .= <<<EOF
<tr>
<td class="regular" colspan="13">$nav</td>
</tr>
</table>
EOF;

$final_output .= small_table("Edit Users", $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>