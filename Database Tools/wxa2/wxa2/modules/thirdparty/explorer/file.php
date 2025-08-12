<?
$file = $_GET["f"]  ;
if(preg_match("/.(jpg|gif|png|)$/i", $file))
	echo "<img src=$file>";
?>