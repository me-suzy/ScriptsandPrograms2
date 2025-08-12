<div align="center">
<?php
echo "<a href=\"index.php?file=\" class=\"button_switch\">".$str["menu2_up"]."</a>\n";
GetDir($dirs,$files,$files_size);
$sort_opt=$_SESSION["s_data"]["sort"] . "sort_" . $_SESSION["s_data"]["sort2"];
unset($dirs);
@usort($files,$sort_opt);
$count["total"]=SizeOf($files);
for($i=0;$i<$count["total"];$i++){
	if ($_SESSION["s_data"]["file"]==$files[$i]["name"]) $count["actual"]=$i;
}
if ($_SESSION["s_data"]["show"]=="list"){
	if ($count["actual"]==0){
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/prev_g.png\" width=\"24\" height=\"24\" alt=\"".$str["menu2_prev"]."\" />";
	} else {
		echo "<a href=\"index.php?file=".$files[($count["actual"]-1)]["name"]."\">";
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/prev.png\" width=\"24\" height=\"24\" alt=\"".$str["menu2_prev"]."\" />";
		echo "</a>";
	}
	echo "&nbsp;&nbsp;";
	
	echo "<a href=\"index.php?file=\">";
	echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/up.png\" width=\"24\" height=\"24\" alt=\"".$str["menu2_up"]."\" />";
	echo "</a>";
	
	echo "&nbsp;&nbsp;";
	if (($count["actual"]+1)==$count["total"]){
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/next_g.png\" width=\"24\" height=\"24\" alt=\"".$str["menu2_next"]."\" />";
	} else {
		echo "<a href=\"index.php?file=".$files[($count["actual"]+1)]["name"]."\">";
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/next.png\" width=\"24\" height=\"24\" alt=\"".$str["menu2_next"]."\" />";
		echo "</a>";
	}
} else if ($_SESSION["s_data"]["show"]=="thumb") {
	if (($count["actual"]!=1)&&($count["actual"]!=0)){
		ShowThumb($count["actual"]-2,true,$image_show_thumb_size,$image_show_thumb_size);
	} else {
		echo "<img src=\"skin/-shared-/spacer100_100.gif\" width=\"".$image_show_thumb_size."\" height=\"".$image_show_thumb_size."\" alt=\"\" />\n";
	}
	if ($count["actual"]!=0){
		ShowThumb($count["actual"]-1,true,$image_show_thumb_size,$image_show_thumb_size);
		echo "<a href=\"index.php?file=" . URLEncode($files[($count["actual"]-1)]["name"]) . "\">";
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/prev_big.png\" width=\"30\" height=\"".$image_show_thumb_size."\" alt=\"".$str["menu2_prev"]."\" /></a>\n";
	} else {
		echo "<img src=\"skin/-shared-/spacer100_100.gif\" width=\"".$image_show_thumb_size."\" height=\"".$image_show_thumb_size."\" alt=\"\" />\n";
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/prev_big_g.png\" width=\"30\" height=\"".$image_show_thumb_size."\" alt=\"".$str["menu2_prev"]."\" />\n";
	}
	echo "<a href=\"index.php?file=\">";
	ShowThumb($count["actual"],false,$image_show_thumb_size,$image_show_thumb_size);
	echo "</a>\n";
	if (($count["actual"]+1)<$count["total"]){
		echo "<a href=\"index.php?file=" . URLEncode($files[($count["actual"]+1)]["name"]) . "\">";
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/next_big.png\" width=\"30\" height=\"".$image_show_thumb_size."\" alt=\"".$str["menu2_next"]."\" /></a>\n";
		ShowThumb($count["actual"]+1,true,$image_show_thumb_size,$image_show_thumb_size);
	} else {
		echo "<img src=\"skin/".$_SESSION["s_data"]["skin"]."/next_big_g.png\" width=\"30\" height=\"".$image_show_thumb_size."\" alt=\"".$str["menu2_next"]."\" />\n";
		echo "<img src=\"skin/-shared-/spacer100_100.gif\" width=\"".$image_show_thumb_size."\" height=\"".$image_show_thumb_size."\" alt=\"\" />\n";
	}
	if (($count["actual"]+2)<$count["total"]){
		ShowThumb($count["actual"]+2,true,$image_show_thumb_size,$image_show_thumb_size);
	} else {
		echo "<img src=\"skin/-shared-/spacer100_100.gif\" width=\"".$image_show_thumb_size."\" height=\"".$image_show_thumb_size."\" alt=\"\" />\n";
	}
	
}
?>
&nbsp;&nbsp;<a href="index.php?page=setup" class="button_setup"><?php echo $str["menu_setup"];?></a>
</div>
