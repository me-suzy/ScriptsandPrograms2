<?php
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
?><LINK href="includes/style.css" rel="stylesheet" type="text/css">
<body class="tblBody"><?
require("includes/config.php");
if($_GET['subDir']){
	$subDir = $_GET['subDir'];
}
else{
	$subDir = $_POST['subDir'];
}
if($_GET['picNum']!=""){
	$j = $_GET['picNum'];
}
else{
	$j = $_POST['picNum'];
}
if($_GET['time']){
	$time = $_GET['time'];
}
else{
	$time = $_POST['time'];
}
$link = $_GET['link'];
$full = $_GET['full'];

if($subDir){
	if(strpos($subDir, "\'")){
		$subDir = str_replace("\'", "'", $subDir);
	}
	$picArray = array();
	if ($dh = opendir($baseDir.$subDir."/")) {
		while (($file = readdir($dh)) !== false) {
			if((filetype($baseDir.$subDir."/".$file)=="file") && (strrpos($file, '.jpg') || strrpos($file, '.JPG'))){
				array_push($picArray, $file);
			}
		}
		closedir($dh);
	}
}
else{
	$picArray = array();
	if ($dh = opendir($baseDir)) {
		while (($file = readdir($dh)) !== false) {
			if((filetype($baseDir.$file)=="file") && (strrpos($file, '.jpg') || strrpos($file, '.JPG'))){
				array_push($picArray, $file);
			}
		}
		closedir($dh);
	}
}
if($time){
	if($j>count($picArray)-2){
		$time = "";
		$message = "<script>alert('No more picture in this album. Slide show is ending...')</script>";
	}
	else{
		?><META HTTP-EQUIV="refresh" content="<?=$time?>; URL=fullPic.php?time=<?=$time?>&subDir=<?=$subDir?>&picNum=<?=$j+1?>"><?
	}
}
if($full){
	if(strpos($link, "\'")){
		$link = str_replace("\'", "'", $link);
	}
	?>
	<table align="center" class="tblBody">
	<tr><td align="center"><input type="button" onClick="history.back();" value="Go Back"></td></tr>
	<tr><td><img src="<?=$virtualPath.$link?>"></td></tr>
	</table>
	<?
}
else{
	list($w, $h) = getimagesize($baseDir.$subDir."/".$picArray[$j]);
	//echo "P ". $w. " X ".$h."<br>";
	if($w > $h && $w > 800){
		$ratio = 800/$w;
		$width = 800;
		$height = $ratio * $h;
		$height = floor($height);
	}				
	else if($h > $w && $h > 600){
		$ratio = 600/$h;
		$height = 600;
		$width = $ratio * $w;
		$width = floor($width);
	}
	else{
		$width = $w;
		$height = $h;
	}
	//echo "A ". $width. " X ".$height;
	if($subDir){
		$link = $subDir."/".$picArray[$j];
	}
	else{
		$link = $picArray[$j];
	}
	?>
	<form action="fullPic.php" method="post">
	<table width="<?=$width?>" class="tblBody" align="center">
		<tr><td class="tblHead2">
			<?
			if($j>0){
				?><a href="fullPic.php?subDir=<?=$subDir?>&picNum=<?=$j-1?>"><font color="#FFFFFF" style="font-weight:bold "><-- Back</font></a><?
			}
			else{
				echo "<-- Back";
			}
			?>
		</td>
		<td class="tblHead2" align="center">
			<a href="fullPic.php?full=true&link=<?=$link?>"><font color="#FFFFFF" style="font-weight:bold ">Original size</font></a>
			<?
			if(!$time){
				?>
				 | Slide Show 
				<select name="time" onChange="submit();">
					<option value="" selected>Time</option>
					<option value="3">3 Sec</option>
					<option value="5">5 Sec</option>
					<option value="7">7 Sec</option>
					<option value="10">10 Sec</option>
					<option value="15">15 Sec</option>
				</select>
				<input type="hidden" name="subDir" value="<?=$subDir?>">
				<input type="hidden" name="picNum" value="<?=$j?>">
				<?
			}
			else{
				?>
				 | <a href="fullPic.php?subDir=<?=$subDir?>&picNum=<?=$j?>"><font color="#FFFFFF" style="font-weight:bold ">Stop slide show</font></a>
				<?
			}
			?>
		</td>
		<td class="tblHead2" align="right">
			<?
			if($j<count($picArray)-1){
				?><a href="fullPic.php?subDir=<?=$subDir?>&picNum=<?=$j+1?>"><font color="#FFFFFF" style="font-weight:bold ">Next --></font></a><?
			}
			else{
				echo "Next -->";
			}
			?>
		</td>
		</tr>
		<tr><td colspan="3"><img src="<?=$virtualPath.$link?>" width="<?=$width?>" height="<?=$height?>"></td></tr>
		<tr><td colspan="3" class="tblHead2" align="center"><?=$link?></td></tr>
	</table>
	</form>
	<?
	echo $message;
}