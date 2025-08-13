<?

/*
 * $Id: users.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
<script>if(top.location == self.location){top.location.href='$base_url/admin/index.php';}</script>
<script>
var ProfilePopUpX = (screen.width/2)-275;
var ProfilePopUpY = (screen.height/2)-200;
var pos = "left="+ProfilePopUpX+",top="+ProfilePopUpY;
function ProfilePopUp(link){
ProfilePopUpWindow = window.open(link,"Profile","scrollbars=yes,resizable=yes,width=550,height=400,"+pos);
}
var ImagePopUpX = (screen.width/2)-320;
var ImagePopUpY = (screen.height/2)-240;
var pos = "left="+ImagePopUpX+",top="+ImagePopUpY;
function ImagePopUp(link){
ImagePopUpWindow = window.open(link,"Image","scrollbars=yes,resizable=yes,width=640,height=480,"+pos);
}
</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
EOF;

if(!isset($sort)){$sort = "";}

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
	case "gender_asc" : $order_by = "sex"; break;
	case "gender_desc" : $order_by = "sex desc"; break;
	case "total_ratings_asc" : $order_by = "total_ratings"; break;
	case "total_ratings_desc" : $order_by = "total_ratings desc"; break;
	case "total_points_asc" : $order_by = "total_points"; break;
	case "total_points_desc" : $order_by = "total_points desc"; break;
	case "avg_rating_asc" : $order_by = "average_rating"; break;
	case "avg_rating_desc" : $order_by = "average_rating desc"; break;
	default : $order_by = "average_rating desc"; break;
}

$sql = "
	select
		$tb_users.id as id,
		$tb_users.username as username,
		$tb_users.realname as realname,
		$tb_users.email as email,
		$tb_users.age as age,
		$tb_users.sex as sex,
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
";

$query = sql_query($sql) or die(mysql_error());

$table .= <<<EOF
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == $tb_users . ".image_status desc")
	$table .= "image_status_disabled";
else
	$table .= "image_status_new";

$table .= <<<EOF
"><b>Status</b></a></td>
	<td><a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == $tb_users . ".id")
	$table .= "id_desc";
else
	$table .= "id_asc";

$table .= <<<EOF
"><b>ID</b></a>&nbsp;</td>
	<td><a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "username")
	$table .= "username_desc";
else
	$table .= "username_asc";

$table .= <<<EOF
"><b>Username</b></a>&nbsp;</td>
<td align="right">&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "age")
	$table .= "age_desc";
else
	$table .= "age_asc";

$table .= <<<EOF
"><b>Age</b></a></td>
<td align="right">&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "sex")
	$table .= "gender_desc";
else
	$table .= "gender_asc";

$table .= <<<EOF
"><b>Sex</b></a></td>
<td align="right">&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "total_ratings")
	$table .= "total_ratings_desc";
else
	$table .= "total_ratings_asc";

$table .= <<<EOF
"><b>Ratings</b></a></td>
<td align="right">&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "total_points")
	$table .= "total_points_desc";
else
	$table .= "total_points_asc";

$table .= <<<EOF
"><b>Points</b></a></td>
<td align="right">&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "average_rating")
	$table .= "avg_rating_desc";
else
	$table .= "avg_rating_asc";

$table .= <<<EOF
"><b>Avg</b></a></td>
<td>&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "comment_count")
	$table .= "comment_count_desc";
else
	$table .= "comment_count_asc";

$table .= <<<EOF
"><b>Com</b></a></td>
<td>&nbsp;<a href="$base_url/admin/users.php?$sn=$sid&amp;sort=
EOF;

if($order_by == "country")
	$table .= "country_desc";
else
	$table .= "country_asc";

$table .= <<<EOF
"><b>Country</b></a></td>
</tr>
EOF;

$cc=0;

while($array = sql_fetch_array($query)){

if($array["country"] == ""){$array["country"] = "None.gif";}
$short_entry = eregi_replace(".gif", "", $array["country"]);
$country = eregi_replace("_", " ", $short_entry);

$cc++;
$cell = "#eeeeee";
$cc % 2  ? 0 : $cell = "#dddddd";

switch($array["image_status"]){
	case 1:
$status = <<<EOF
<font color="blue">Approved</font>
EOF;
		break;
	case 0:
$status = <<<EOF
<font color="green">New</font>
EOF;
		break;
	case -1:
$status = <<<EOF
<font color="red">Disabled</font>
EOF;
}

$table .= <<<EOF
<tr bgcolor="$cell">
<td class="regular"><a href="javascript:ProfilePopUp('$base_url/admin/user_profile.php?$sn=$sid&amp;id=$array[id]');">Profile</a> &nbsp;|</td>
<td class="regular"><a href="javascript:ImagePopUp('$base_url/admin/user_image.php?$sn=$sid&amp;userid=$array[id]');">Image</a></td><td class="regular">$status</td>
<td class="regular">$array[id]</td>
<td class="regular"><a href="mailto:$array[realname] <$array[email]>">$array[username]</a></td>
<td class="regular" align="right">$array[age]</td>
<td class="regular" align="center">$array[sex]</td>
<td class="regular" align="right">$array[total_ratings]</td>
<td class="regular" align="right">$array[total_points]</td>
<td class="regular" align="right">&nbsp;$array[average_rating]</td>
<td class="regular" align="right">&nbsp;$array[comment_count]</td>
<td class="regular" align="center"><img align="top" border="1" height="13" width="20" src="$base_url/images/flags/$array[country]" hspace="5" alt="$country" title="$country" /></td>
</tr>
EOF;

}

$table .= <<<EOF
</table>
EOF;

$content .= small_table("Edit Users", $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: users.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>