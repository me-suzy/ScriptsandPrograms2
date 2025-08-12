<?php
@include "./config.php";
if (empty($_SESSION["s_data"]["lang"])) $_SESSION["s_data"]["lang"]=$default_language;
if (empty($_SESSION["s_data"]["skin"])) $_SESSION["s_data"]["skin"]=$default_skin;
if (empty($_SESSION["s_data"]["quality"])) $_SESSION["s_data"]["quality"]=$default_quality;
if (empty($_SESSION["s_data"]["res"])) $_SESSION["s_data"]["res"]=$default_res;
if (empty($_SESSION["s_data"]["show"])) $_SESSION["s_data"]["show"]=$default_show;
if (empty($_SESSION["s_data"]["slideshow_timer"])) $_SESSION["s_data"]["slideshow_timer"]=$slideshow_timer;
@include "./skin/" . $_SESSION["s_data"]["skin"] . "/colors.php";
@include "./lang/" . $_SESSION["s_data"]["lang"] . ".php";
//////////////
// FUNCTION //
//////////////
function cz2en($vstup_str){ //Odstranìní háèkù z textu...
	$cz=array("È","è","Ï","ï","Ì","ì","Ò","ò","Ø","ø","","","","","Ù","ù","","");
	$en=array("C","c","D","d","E","e","N","n","R","r","S","s","T","t","U","u","Z","z");
	return Str_Replace($cz,$en,$vstup_str);
}
function Type_Flag2Text($type){
	global $str;
	switch ($type) {
    	case 1:
        	return("GIF");
    	case 2:
        	return("JPG");
		case 3:
			return("PNG");
		default:
			return($str["list_unknown"]);
	}
	//1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP,
	//7 = TIFF(intel byte order), 8 = TIFF(motorola byte order),
	//9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF
}
function GetDir(&$dirs,&$files,&$files_size){
	global $str;
	$files_size=0;
	if (StrStr($_SESSION["s_data"]["dir"],"..")) $_SESSION["s_data"]["dir"]="/";
	$dir="./_images/" . $_SESSION["s_data"]["dir"];
	if (@is_dir($dir)){
		$adr=@dir($dir);
		$id=0;
		while ($file=@$adr->read()){
			if (($file==".")||($file=="..")||($file=="index.php")||($file=="_info.txt")){}
			else {
				if (@is_dir($dir . $file . "/")){
					$dirs[$id]["name"]=$file;
					$dirs[$id]["type"]=$str["dir"];
					$dirs[$id]["time"]=filemtime($dir . $file ."/");
				} else {
					if (!StrStr($file,"_thumb.jpg")){
						$info=getimagesize($dir . $file);
						$files[$id]["name"]=$file;
						$files[$id]["size"]=filesize($dir . $file);
						$files[$id]["time"]=filemtime($dir . $file);
						$files[$id]["type"]=Type_Flag2Text($info[2]);
						$files_size=$files_size+$files[$id]["size"];
						if (!empty($info[0])){
							$files[$id]["res"]=$info[0] . "x" . $info[1];
						}
					}
				}
			}
			$id++;
		}
		@$adr->close();
	} else {
		echo "<div class=\"error\">" . $str["error_dir"] . "&nbsp;<a href=\"index.php?dir=\">-OK-</a></div>";
	}
}
function OneDirUp($dir){
	$tmp_dir=Explode("/",$dir);
	$max=SizeOf($tmp_dir)-2;
	$newdir="/";
	for ($i=1;$i<$max;$i++){
		$newdir.=$tmp_dir[$i] . "/";
	}
	return($newdir);
}
function ShowThumb($i,$show_link=true,$width=100,$height=100){
	global $files,$delete_old_thumbs;
	$class="thumb2";
	if ($show_link){
		echo "<a href=\"index.php?file=" . URLEncode($files[$i]["name"]) . "\">";
		$class="thumb";
	}
	if (file_exists("./_images/" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg")){
		$filemtime_thumb=filemtime("./_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg");
		$filemtime_file=filemtime("./_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"]);
		if (($filemtime_file>$filemtime_thumb)||($delete_old_thumbs>$filemtime_thumb)){
			// Regenerating Thumb
			echo "<img class=\"".$class."\" src=\"thumb_file.php?file=".URLEncode($files[$i]["name"])."&amp;tmp=".URLEncode($_SESSION["s_data"]["dir"])."&amp;".SID."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
		} else {
			// Loading Thumb
			echo "<img class=\"".$class."\" src=\"_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
		}
	} else {
		// Generating THUMB
		echo "<img class=\"".$class."\" src=\"thumb_file.php?file=".URLEncode($files[$i]["name"])."&amp;tmp=".URLEncode($_SESSION["s_data"]["dir"])."&amp;".SID."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
	}
	if ($show_link){echo "</a>";}
	echo "\n";
}
function ShowThumbDir($i,$show_link=true,$width=100,$height=100){
	global $files,$delete_old_thumbs;
	$class="thumb2";
	if ($show_link){
		$newdir=$_SESSION["s_data"]["dir"] . $files[$i]["name"] . "/";
		echo "<a href=\"index.php?dir=" . $newdir . "\">";
		$class="thumb";
	}
	if (file_exists("./_images/" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg")){
		$filemtime_thumb=filemtime("./_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg");
		$filemtime_file=filemtime("./_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"]);
		if (($filemtime_file>$filemtime_thumb)||($delete_old_thumbs>$filemtime_thumb)){
			// Regenerating Thumb
			echo "<img class=\"".$class."\" src=\"thumb_dir2.php?text=".URLEncode($files[$i]["name"])."&".SID."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
		} else {
			// Loading Thumb
			echo "<img class=\"".$class."\" src=\"_images" . $_SESSION["s_data"]["dir"] . $files[$i]["name"] . "_thumb.jpg\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
		}
	} else {
		// Generating THUMB
		echo "<img class=\"".$class."\" src=\"thumb_dir2.php?text=".URLEncode($files[$i]["name"])."&".SID."\" width=\"".$width."\" height=\"".$height."\" alt=\"".$files[$i]["name"]."\" />";
	}
	if ($show_link){echo "</a>";}
	echo "\n";
}
function ShowSize($size){
	$bytes[0]="B";
	$bytes[1]="kB";
	$bytes[2]="MB";
	$bytes[3]="GB";
	$bytes[4]="TB";
	$i=0;
	while ($size>1023){
		$size=$size/1024;
		$i++;
	}
	$size=round($size,2);
	if ($size==0){
		$size=" ";
	}else {
		$size.=" " . $bytes[$i];
	}
	return ($size);
}
function ShowDate($date){
	global $str;
	return (Date($str["date_format"],$date));
}
// SORTOVACI FUNKCE - START
function namesort_asc($v1, $v2){
	return strcmp($v1['name'], $v2['name']);
}
function namesort_desc($v1, $v2){
	return strcmp($v2['name'], $v1['name']);
}

function typesort_asc($v1, $v2){
	return strcmp($v1['type'], $v2['type']);
}
function typesort_desc($v1, $v2){
	return strcmp($v2['type'], $v1['type']);
}

function sizesort_asc($v1, $v2){
	if ($v1['size'] == $v2['size']) return 0;
	return ($v1['size'] < $v2['size']) ? -1 : 1;
}
function sizesort_desc($v1, $v2){
	if ($v1['size'] == $v2['size']) return 0;
	return ($v1['size'] > $v2['size']) ? -1 : 1;
}

function timesort_asc($v1, $v2){
	if ($v1['time'] == $v2['time']) return 0;
	return ($v1['time'] < $v2['time']) ? -1 : 1;
}
function timesort_desc($v1, $v2){
	if ($v1['time'] == $v2['time']) return 1;
	return ($v1['time'] > $v2['time']) ? -1 : 1;
}
// SORTOVACI FUNKCE - KONEC
function txt2UTF($vstup_str){ //Tato funkce neni pouita! Pouívá se v kombinaci s TTF fonty...
  $tabulkaUTF = array("È"=>268, "è"=>269,
                      "Ï"=>270, "ï"=>271,
                      "Ì"=>282, "ì"=>283,
                      "Ò"=>327, "ò"=>328,
                      "Ø"=>344, "ø"=>345,
                      ""=>352, ""=>353,
                      ""=>356, ""=>357,
                      "Ù"=>366, "ù"=>367,
                      ""=>381, ""=>382);
  $vystup_str = ""; // vynuluji výstupní øetìzec
  for($i=0; $i < strlen($vstup_str); $i++) // projdu vechny znaky vstupního øetìzce
  {
    if ($tabulkaUTF[$vstup_str[$i]]) // pokud se znak nachází v tabulce
      $vystup_str .= "&#" . $tabulkaUTF[$vstup_str[$i]] . ";"; // zamìním jej
    else
      $vystup_str .= $vstup_str[$i]; // jinak vezmu pùvodní znak
  }
  return $vystup_str; // vracím pøekódovaný øetìzec
}
?>