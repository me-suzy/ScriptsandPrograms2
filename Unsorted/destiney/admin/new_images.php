<?php
include("./config.php");
include("$include_path/common.php");

check_login();

include("$include_path/$table_file");

if(isset($_POST['submit'])){

$ud_sql = "
	update
		$tb_users
	set
		image_status = '$_POST[image_status]',
		user_type = '$_POST[new_user_type]'
	where
		id = '$_POST[update_id]'
";

$ud_query = mysql_query($ud_sql) or die(mysql_error());
}

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Validate New Image</title>
EOF;

if(isset($_POST['submit'])){
$final_output .= <<<EOF
<script>
window.opener.parent.main.location.reload();
window.close();	
</script>
EOF;
}

include("$include_path/styles.php");

$final_output .= <<<EOF
<script>
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

$pop = isset($_GET['pop']) ? $_GET['pop'] : 0;

if($pop != 1) $final_output .= "";

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
EOF;

$show = isset($_GET['show']) ? $_GET['show'] : "";

if($show == "user"){

if(isset($_GET['id'])){
	$id = $_GET['id'];
	$img_src = get_image($_GET['id']);
} else {
	echo "No user 'id' submitted..";
	exit();
}


$sql = "
	select
		user_type
	from
		$tb_users
	where
		id = '$id'
";
$query = mysql_query($sql) or die(mysql_error());
$array = mysql_fetch_array($query);
$user_type_options = get_user_types($array["user_type"]);

$table .= <<<EOF
<tr>
<td>
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
	<td bgcolor="black">$img_src</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" border="0" align="center">
<tr><form method="post" action="$base_url/admin/new_images.php">
	<td class="regular" nowrap>User Type: <select name="new_user_type">$user_type_options</select></td>
	<td class="regular" nowrap><input type="radio" name="image_status" value="disabled"> Disable</td>
	<td class="regular" nowrap><input type="radio" name="image_status" value="queued" checked> Leave Queued</td>
	<td class="regular" nowrap><input type="radio" name="image_status" value="approved"> Approve</td>
	<td class="regular"><input type="submit" name="submit" value=" Go -> " /></td>
</tr>
<tr>
	<td colspan="5" align="center"></td>
</tr>
<input type="hidden" name="sn" value="$sn" />
<input type="hidden" name="sid" value="$sid" />
<input type="hidden" name="update_id" value="$id" />
<!-- <input type="hidden" name="show" value="user" /> -->
</form>
</table>
</td>
</tr>
EOF;

} else {

$sql = "
	select
		*
	from
		$tb_users
	where
		image_status = 'queued'
	order by
		timestamp desc
";

$query = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($query)){

$table .= <<<EOF
<tr>
	<td class="bold">Username</td>
</tr>
EOF;

while($array = mysql_fetch_array($query)){

$table .= <<<EOF
<tr>
	<td class="regular"><a href="javascript:ImagePopUp('$base_url/admin/new_images.php?show=user&amp;id=$array[id]&amp;pop=1');">$array[username]</a></td>
</tr>
EOF;

}

} else {

$table .= <<<EOF
<tr>
<td class="regular">No new images to validate.</td>
</tr>
EOF;

}

}

$table .= <<<EOF
</table>
EOF;

$title_text = "Recently Updated Images";
if($pop == 1) $title_text = "Validate New Image";

$final_output .= small_table($title_text, $table);

$final_output .= <<<EOF
</body>
</html>
EOF;

echo $final_output;

?>