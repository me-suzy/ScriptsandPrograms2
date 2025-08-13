<?

/*
 * $Id: new_images.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

if(isset($submit)){

$ud_sql = "
	update
		$tb_users
	set
		image_status = '$image_status'
	where
		id = '$update_id'
";

$ud_query = sql_query($ud_sql);
}

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>pRated</title>
EOF;

if(isset($submit)){
$content .= <<<EOF
<script>
window.opener.parent.main.location.reload();
window.close();	
</script>
EOF;
}

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content .= <<<EOF
$styles
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

if(!isset($pop)) $pop = 0;

if($pop != 1) $content .= "";

$table = <<<EOF
<table cellpadding="5" cellspacing="0" border="0">
EOF;

if(!isset($show)) $show = "";

if($show == "user"){

$img_src = get_image($id);

$table .= <<<EOF
<tr>
<td>
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
	<td bgcolor="black"><img src="$img_src"></td>
</tr>
</table>
<table cellpadding="10" cellspacing="0" border="0" align="center">
<tr><form method="post" action="$base_url/admin/new_images.php?$sn=$sid">
	<td class="regular"><input type="radio" name="image_status" value="1" checked /> Approve</td>
	<td class="regular"><input type="radio" name="image_status" value="0" /> Ignore</td>
	<td class="regular"><input type="radio" name="image_status" value="-1" /> Deny</td>
	<td class="regular"><input type="submit" name="submit" value=" Go -> " /></td>
</tr>
<tr>
	<td colspan="4" align="center"></td>
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
		image_status = '0'
	order by
		timestamp desc
";

$query = sql_query($sql);

if(sql_num_rows($query)>0){

$table .= <<<EOF
<tr>
	<td class="bold">Username</td>
</tr>
EOF;

while($array = sql_fetch_array($query)){

$table .= <<<EOF
<tr>
	<td class="regular"><a href="javascript:ImagePopUp('$base_url/admin/new_images.php?$sn=$sid&show=user&id=$array[id]&pop=1');">$array[username]</a></td>
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

$content .= small_table($title_text, $table);

$content .= <<<EOF
</body>
</html>
EOF;

echo $content;

/*
 * $Id: new_images.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
