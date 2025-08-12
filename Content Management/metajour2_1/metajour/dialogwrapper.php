<?php
/*
The dialogwrapper is needed to avoid that form submits inside the modal og modeless
dialog opens a separate window
*/

/* 
force the use of gui.php, when showpage.php occurs in the query-string
*/
$qstr = $_SERVER['QUERY_STRING'];
$p = strpos($qstr,'showpage.php');
if ($p > 0) {
	$qstr = 'gui.php'.substr($qstr,$p+12);
}

?>
<html>
<head><title>METAjour</title></head>
<body>
<iframe src="<?php echo $qstr ?>" 
<?php 
if (isset($_REQUEST['__list'])) {
	$width = 1000;
	$height = 700;
} else {
	$width = 500;
	$height = 650;
}

$scroll = 'no';
if (isset($_GET['_width'])) $width = $_GET['_width'];
if (isset($_GET['_height'])) $height = $_GET['_height'];
if (isset($_GET['_scroll'])) $scroll = $_GET['_scroll'];
echo 'width="'.$width.'" height="'.$height.'"'; 

?>
frameborder="0" marginwidth="0" marginheight="0" 
<?php 
if (isset($_REQUEST['__wizard'])) {
	echo '';
} elseif (isset($_REQUEST['__list'])) {
	echo '';
} else {
	echo 'scrolling="'.$scroll.'"';
}
?>
>
</body>
</html>
