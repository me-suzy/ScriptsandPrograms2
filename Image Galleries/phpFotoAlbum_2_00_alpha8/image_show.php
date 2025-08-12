<Script language="JavaScript">
function keyCtrl()
{
	//alert("Keycode: "+window.event.keyCode);
	if (window.event.keyCode == 32)
		window.location="<?php echo "index.php?file=".$files[($count["actual"]+1)]["name"]; ?>";
}
</script>
<?php
if (($_SESSION["s_data"]["res"]=="orig")&&($allow_direct_original)){
	$info=getimagesize("./_images".$_SESSION["s_data"]["dir"].$_SESSION["s_data"]["file"]);
	echo "<a href=\"index.php?file=\">";
	echo "<img src=\"_images".$_SESSION["s_data"]["dir"].$_SESSION["s_data"]["file"]."?tmp=".Date("YmdHis")."\" class=\"image\" alt=\"".$_SESSION["s_data"]["file"]."\" ".$info[3]." />";
	echo "</a>\n<br />";
	echo "<img src=\"skin/-shared-/logo.png?tmp=".Date("YmdHis")."\" class=\"image\" alt=\"\" width=\"120\" height=\"20\"/>";
} else {
	if ($_SESSION["s_data"]["res"]=="orig"){
		$info=getimagesize("./_images".$_SESSION["s_data"]["dir"].$_SESSION["s_data"]["file"]);
		$res=$info[3];
	} else {
		List($resx,$resy)=Explode("x",$_SESSION["s_data"]["res"]);
		$res="width=\"".$resx."\" height=\"".$resy."\"";
	}
	echo "<a href=\"index.php?file=\">";
	echo "<img src=\"image_show_gen.php?".SID."&amp;tmp=".Date("YmdHis")."\" class=\"image\" alt=\"".$_SESSION["s_data"]["file"]."\" ".$res." />";
	echo "</a>\n";
}
?>
<div class="small"><a href="image_download.php?<?php echo SID; ?>"><?php echo $str["download_full_res"]; ?></a></div>