<?php
function options($array){
	global $$array;
	foreach ($$array as $id => $name) {
		if (Trim($_SESSION["s_data"][$array])==Trim($id)){
			echo "<option value=\"".$id."\" SELECTED>".$name."</option>\n";
		} else {
			echo "<option value=\"".$id."\">".$name."</option>\n";
		}
	}
}
echo "<h1>".$str["setup"]."</h1>\n";
@include "./skin/_skins.php";
@include "./lang/_langs.php";
?>
<form class="form_out" action="set_post.php" method="POST">
<input type="hidden" name="page" value="" />
<?php echo $str["setup_skin"];?>&nbsp;
<select name="skin" class="form">
<?php options("skin"); ?>
</select><br />
<?php echo $str["setup_lang"];?>&nbsp;
<select name="lang" class="form">
<?php options("lang"); ?>
</select><br />
<?php echo $str["setup_res"];?>&nbsp;
<select name="res" class="form">
<?php options("res"); ?>
</select><br />
<?php echo $str["setup_quality"];?>&nbsp;
<select name="quality" class="form">
<?php
for ($i=1;$i<11;$i++){
	if (Trim($_SESSION["s_data"]["quality"])==($i*10)){
		echo "<option value=\"".($i*10)."\" SELECTED>".($i*10)."%</option>\n";
	} else {
		echo "<option value=\"".($i*10)."\">".($i*10)."%</option>\n";
	}
}
?>
</select><br /><br />
<input type="submit" value="<?php echo $str["setup_submit"];?>" class="form" />
</form>