<?php
GetDir($dirs,$files,$files_size);
$sort_opt=$_SESSION["s_data"]["sort"] . "sort_" . $_SESSION["s_data"]["sort2"];
@usort($dirs,$sort_opt);
@usort($files,$sort_opt);
$count["dirs"]=SizeOf($dirs);
$count["files"]=SizeOf($files);
if ((is_array($dirs))&&(is_array($files))) {
	$files=array_merge($dirs,$files);
}
if ((is_array($dirs))&&(!is_array($files))) {
	$files=$dirs;
}
/*if ((!is_array($dirs))&&(is_array($files))) {
	$files=$files;
}*/
unset($dirs);
$DirUp=OneDirUp($_SESSION["s_data"]["dir"]);
if ($_SESSION["s_data"]["show"]=="list"){
	echo "<table class=\"list\" cellspacing=\"0\">\n";
	echo "<tr><td colspan=\"5\" style=\"background-color: " . $table_color[1] . ";\">\n";
	echo "<img src=\"skin/". $_SESSION["s_data"]["skin"] ."/list_dir.png\" width=\"16\" height=\"16\" alt=\"\" />\n";
	echo "<a href=\"index.php?dir=/\">" . $str["list_root"] . "</a>\n";
	echo "</td></tr><tr><td colspan=\"5\" style=\"background-color: " . $table_color[0] . ";\">\n";
	echo "<img src=\"skin/". $_SESSION["s_data"]["skin"] ."/list_dir.png\" width=\"16\" height=\"16\" alt=\"\" />\n";
	echo "<a href=\"index.php?dir=" . $DirUp . "\">" . $str["list_up"] . "</a>\n";
	echo "</td></tr>\n";
	$q=0;
	for ($i=0;$i<SizeOf($files);$i++){
		if ($q==1){$q=0;} else {$q=1;}
		echo "<tr><td style=\"background-color: " . $table_color[$q] . ";\">\n";
		if ($files[$i]["type"] == $str["dir"]){
			$newdir=$_SESSION["s_data"]["dir"] . $files[$i]["name"] . "/";
			echo "<img src=\"skin/". $_SESSION["s_data"]["skin"] ."/list_dir.png\" width=\"16\" height=\"16\" alt=\"\" />\n";
			echo "<a href=\"index.php?dir=" . $newdir . "\">";
		} else if ($files[$i]["type"] == $str["list_unknown"]){
			echo "<img src=\"skin/". $_SESSION["s_data"]["skin"] ."/list_file.png\" width=\"16\" height=\"16\" alt=\"\" />\n";
			echo "<a href=\"index.php?file=" . $files[$i]["name"] . "\">";
		} else {
			echo "<img src=\"skin/". $_SESSION["s_data"]["skin"] ."/list_".strtolower($files[$i]["type"]).".png\" width=\"16\" height=\"16\" alt=\"\" />\n";
			echo "<a href=\"index.php?file=" . $files[$i]["name"] . "\">";
		}
		echo $files[$i]["name"] . "</a>&nbsp;&nbsp;</td>\n";
		echo "<td style=\"background-color: " . $table_color[$q] . ";\">" . ShowSize($files[$i]["size"]) . "&nbsp;&nbsp;</td>\n";
		echo "<td style=\"background-color: " . $table_color[$q] . ";\">" . $files[$i]["type"] . "&nbsp;&nbsp;</td>\n";
		echo "<td style=\"background-color: " . $table_color[$q] . ";\">" . ShowDate($files[$i]["time"]) . "&nbsp;&nbsp;</td>\n";
		echo "<td style=\"background-color: " . $table_color[$q] . ";\">" . $files[$i]["res"] . "&nbsp;&nbsp;</td></tr>\n";
	}
	echo "</table>\n";
} else if ($_SESSION["s_data"]["show"]=="thumb"){
	echo "<a href=\"index.php?dir=/\">";
	echo "<img class=\"thumb\" src=\"thumb_dir.php?text=".URLEncode($str["list_root"])."&".SID."\" width=\"100\" height=\"100\" alt=\"".$str["list_root"]."\" />";
	echo "</a>\n<a href=\"index.php?dir=" . $DirUp . "\">";
	echo "<img class=\"thumb\" src=\"thumb_dir.php?text=".URLEncode($str["list_up"])."&".SID."\" width=\"100\" height=\"100\" alt=\"".$str["list_up"]."\" />";
	echo "</a>\n";
	for ($i=0;$i<SizeOf($files);$i++){
		if ($files[$i]["type"] == $str["dir"]){
			ShowThumbDir($i);
		} else {
			ShowThumb($i);
		}
	}
} else {
	echo "<div class=\"error\"> " . $str["error_listing"] . "</div>";
}
$str["count"]=Str_Replace("%1",$count["dirs"],$str["count"]);
$str["count"]=Str_Replace("%2",$count["files"],$str["count"]);
$files_size=ShowSize($files_size);
if ($files_size==" ") $files_size="0 B";
$str["count"]=Str_Replace("%3",$files_size,$str["count"]);
echo "<div class=\"small\">".$str["count"]."</div>\n";
if (($zip_download)&&($count["files"]>0)) {
	echo "<div class=\"small\"><a href=\"image_download_zip.php?dir=".urlencode($_SESSION["s_data"]["dir"])."&amp;".SID."\">ZIP DOWNLOAD (experimental)</a></div>\n";
}
?>