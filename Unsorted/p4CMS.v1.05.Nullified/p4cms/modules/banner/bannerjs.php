<?
 include("../../include/config.inc.php"); 
 include("../../include/mysql-class.inc.php");
 include("../../include/functions.inc.php");
 
$sql =& new MySQLq();



$sql->Query("SELECT * FROM " . $sql_prefix . "banner WHERE 
(views<max_views OR max_views='0')

AND 

(hits<max_hits OR max_hits='0')

AND bannerzone='$_REQUEST[bannerzone]' 
order by id asc");

$banners = array();

while ($row = $sql->FetchRow()) {
	$banners[count($banners)+1] = $row;	
}

mt_srand ((double)microtime()*1000000);
$randval = mt_rand(1, count($banners));

$row = $banners[$randval];

$bannid = $row->id;

if($row->format=="flash"){
	$src = "<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0\" width=\"$row->flash_width\" height=\"$row->flash_height\"><param name=movie value=\"$row->src\"><param name=quality value=high><embed src=\"$row->src\" quality=high pluginspage=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" type=\"application/x-shockwave-flash\" width=\"$row->flash_width\" height=\"$row->flash_height\"></embed></object>";
	} else {
	$src = "<a target=\"$row->link_target\" href=\"http://".$_SERVER['HTTP_HOST']."/p4cms/modules/banner/adclick.php?bannerid=$row->id\"><img alt=\"$row->alt\" border=\"0\" src=".$row->src."></a>"; $end="image";
	} 
	$views = $row->views;
	
	if($row->src==""){$src="";}
	echo "document.write('$src');\r\n";
	
	
	
	$sql2 =& new MySQLq();
	$sql2->Query("UPDATE " . $sql_prefix . "banner SET views=views+1 where id='$bannid'");
	
	//print_r($row); print_r($banners); echo($randval); 
 ?>
