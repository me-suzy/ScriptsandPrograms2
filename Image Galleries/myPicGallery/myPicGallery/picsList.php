<?
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
?><LINK href="includes/style.css" rel="stylesheet" type="text/css">
<body class="tblBody">
<script>
function openWindow(url, winName){
	window.open(url, winName, 'menubar=no,scrollbars=yes,resizable=yes,width='+screen.width+', height='+screen.height);
}

</script><?
require("includes/config.php");
$subDir = $_GET['subDir'];
$limit = $_GET['limit'];
$page = $_GET['page'];

$picArray = array();

if (!($limit)){
	$limit = 35;
} 
if (!($page)){
	$page = 0;
} 

if($subDir){
	$allow = false;
	if($userType == "admin"){
		$allow = true;
	}
	else{
		if($thePos = strpos($subDir, "/")){
			$myDir = substr($subDir, 0, $thePos);
		}
		else{
			$myDir = $subDir;
		}
		$rs = mysql_query("select * from album_permission where (dir = '$myDir' and userAllowed = '$userName')")or die(mysql_error());
		if($rowRs = mysql_fetch_array($rs)){
			$allow = true;
		}
	}
	if($allow){
		if(strpos($subDir, "\'")){
			$subDir = str_replace("\'", "'", $subDir);
		}
		?><table class="tblBody" align="center">
		<tr><td colspan="7"><b><?=$subDir?></b></td></tr><?
		if ($dh = opendir($baseDir.$subDir."/")) {
			$i=0;
			$k=0;
			while (($file = readdir($dh)) !== false) {
				if((filetype($baseDir.$subDir."/".$file)=="file") && (strrpos($file, '.jpg') || strrpos($file, '.JPG'))){
					if(($k>=$page) && ($k<$page+$limit)){
						if($i==0 || $i%7==0){ echo "<tr>";}
						//$pos = strrpos($subDir);
						//echo "this".$pos;
						if(!file_exists($thumbDir.substr($subDir, -3)."_".$file)){
							list($w, $h) = getimagesize($baseDir.$subDir."/".$file);
							$srcImage = imagecreatefromjpeg($baseDir.$subDir."/".$file);
							$dstImage = imagecreatetruecolor(95, 70);
							imagecopyresized($dstImage, $srcImage , 0, 0, 0, 0, 95, 70, $w, $h);
							imagejpeg($dstImage, $thumbDir.substr($subDir, -3)."_".$file, 100);
						}
						if(strpos($subDir, "'")){
							$subDir = str_replace("'", "\'", $subDir);
						}
						?>
						<td><img width="95" height="70" src="<?=$virtualPathThumb.substr($subDir, -3)."_".$file?>" onClick="openWindow('fullPic.php?subDir=<?=$subDir?>&picNum=<?=$k?>', 'Enlarged');"></td><?
						if(strpos($subDir, "\'")){
							$subDir = str_replace("\'", "'", $subDir);
						}
					}
					$i++;
					if($i%7==0){echo "</tr>";}
					$k++;
				}
			}
			closedir($dh);
		}
	}
	?></table><?
}
else{
	?><table class="tblBody">
	<tr><td colspan="7"><b><?=$subDir?></b></td></tr><?
	if ($dh = opendir($baseDir)) {
		$i=0;
		$k=0;
		while (($file = readdir($dh)) !== false) {
			if((filetype($baseDir.$file)=="file") && (strrpos($file, '.jpg') || strrpos($file, '.JPG'))){
				if(($k>=$page) && ($k<$page+$limit)){
					if($i==0 || $i%7==0){ echo "<tr>";}
					//$pos = strrpos($subDir);
					if(!file_exists($thumbDir.substr($subDir, -3)."_".$file)){
						list($w, $h) = getimagesize($baseDir.$subDir."/".$file);
						$srcImage = imagecreatefromjpeg($baseDir.$subDir."/".$file);
						$dstImage = imagecreatetruecolor(95, 70);
						imagecopyresized($dstImage, $srcImage , 0, 0, 0, 0, 95, 70, $w, $h);
						imagejpeg($dstImage, $thumbDir.substr($subDir, -3)."_".$file, 100);
					}
					?><td><img width="95" height="70" src="<?=$virtualPathThumb.substr($subDir, $pos-3)."_".$file?>" onClick="openWindow('fullPic.php?picNum=<?=$k?>', 'Enlarged');"></td><?
				}
				$i++;
				if($i%7==0){echo "</tr>";}
				$k++;
			}
		}
		closedir($dh);
	}
	?></table><?
}
$numrows = $i;
if ($numrows == 0){
	echo("<center><b>Please select an album from the left to view pictures</b></center>"); 
	exit();
}
$pages = intval($numrows/$limit); 
if ($numrows%$limit) {
	$pages++;
} 

$current = ($page/$limit) + 1; 

if (($pages < 1) || ($pages == 0)) {
	$total = 1;
} 
else {
	$total = $pages;
} 

$first = $page + 1; 

if (!((($page + $limit) / $limit) >= $pages) && $pages != 1) {
	$last = $page + $limit;
}
else{
	$last = $numrows;
} 

?><p align="center"><?

if (($page != 0) && $i>35){ 
	$back_page = $page - $limit;
	echo("<a href=\"$PHP_SELF?subDir=$subDir&page=$back_page&limit=$limit\">Back</a>\n");
}

for ($j=1; $j <= $pages; $j++) {
	$ppage = $limit*($j - 1);
	if (($ppage == $page) && $i>35){
		echo("<b>$j</b>\n");
	} 
	else if($i>35){
		echo("<a href=\"$PHP_SELF?subDir=$subDir&page=$ppage&limit=$limit\">$j</a>\n");
	}
}

if (!((($page+$limit) / $limit) >= $pages) && $pages != 1 && $i>35) { 
	$next_page = $page + $limit;
	echo("<a href=\"$PHP_SELF?subDir=$subDir&page=$next_page&limit=$limit\">Next</a>\n");
}
?>
</body>