<?php
/*
--------^^wpBlog 0.4^^--------
©2003-2005 Wire Plastik Design
functions.php
------------------------------
*/

function formatTime($timestamp){
	global $params;
	$time = $timestamp;
	$tyear = substr($time, 0, 4);
	$tmonth = substr($time, 4, 2);
	$tday = substr($time, 6, 2);
	$thour = substr($time, 8, 2);
	$tminute = substr($time, 10, 2);
	$tsecond = substr($time, 12, 2);
	$mktime = mktime($thour, $tminute, $tsecond, $tmonth, $tday, $tyear)+$params['timediff']*3600;  
    $formatted = date("F j, Y, g:i a",$mktime); 
    return $formatted;
}

function getMonthName($monthno) {
	$months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
	return($months[$monthno]);
}

function checkLogin(){
	global $params;
	if( !isset($_SESSION['password']) || $_SESSION['password'] != $params['password']){
		die("Access Denied.");
	}
}

function dologout(){
	unset($_SESSION['password']);
	unset($_POST['password']);
	header("location: ".$_SERVER['PHP_SELF']);
}

function getImages(){
	$imageArray = Array();
	$imghandle = opendir("images");
	while($imgfile = readdir($imghandle)){
		$fileext = substr($imgfile,-3);
		if($fileext == "gif" || $fileext == "jpg" || $fileext == "png"){
			$imageArray[] = $imgfile;
		}
	}
	if(count($imageArray)>=1){
		return $imageArray;
	}
	else{
		return false;
	}
}

function returnImage($imageArray,$currpost){
	if(is_array($imageArray)){
		$imgcount = count($imageArray);
		$imgno = $currpost%$imgcount;
		$imgstr = "<img src=\"images/".$imageArray[$imgno]."\" alt=\"\" class=\"blogimage\" />";
		return $imgstr;
	}
}
?>