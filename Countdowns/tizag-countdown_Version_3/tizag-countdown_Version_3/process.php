<html>
<head>
<link rel="stylesheet" type="text/css" 
href="default.css" />
<title>Tizag Graphic Countdown</title>
<script type="text/javascript">
<!--
function delayer(){
document.location = "htmlcode.php"
}
//-->
</script>
</head>
<body onLoad="setTimeout('delayer()', 5000)">
<h2>Generating Countdown</h2>
<p>Please wait one moment....
</p>

<?php
include("functions.php");
//Form Variables
$imgfile = 		"pics/".$HTTP_POST_FILES['imgfile']['name'];
$text = 		$_POST['user_text'];
$date = 		$_POST['user_date'];
$mode = 		$_POST['mode'];
$dropshadow = 	$_POST['dropshadow'];
$border = 		$_POST['border'];
$picture = 		$_POST['picture'];
$font_size = 	$_POST['font_size'];
$xpos = 		$_POST['xpos'];
$ypos = 		$_POST['ypos'];
$xposoff = 		$_POST['xposoff'];
$yposoff = 		$_POST['yposoff'];

//Combine the color into the format RRR.GGG.BBB
$bgcolor = 		colorCombine($_POST['bgred'], $_POST['bggreen'], $_POST['bgblue']);
$txtcolor = 	colorCombine($_POST['txtred'], $_POST['txtgreen'], $_POST['txtblue']);
$shadowcolor = 	colorCombine($_POST['shadowred'], $_POST['shadowgreen'], $_POST['shadowblue']);
$bordercolor = 	colorCombine($_POST['borderred'], $_POST['bordergreen'], $_POST['borderblue']);

if($xpos == '')
	$xpos = 0;
if($ypos == '')
	$ypos = 0;
if($xposoff == '')
	$xposoff = 0;
if($yposoff == '')
	$yposoff = 0;


if($picture == 1){
	$fh = fopen('pics/data.dat', 'r') or die("Cannot Open File!");
	//Retrieve Information From data.dat, ORDER IS IMPORTANT!
	$imgfile = 	trim(fgets($fh, 1024));
}

if($picture == 2){
	if (move_uploaded_file($HTTP_POST_FILES['imgfile']['tmp_name'], $imgfile)) {
	   print "File is valid, and was successfully uploaded. ";
	} else {
	   print "Upload Failed";
	}
	$picture = 1;
}


if(!isset($dropshadow))
	$dropshadow = 0;
if(!isset($border))
	$border = 0;

$fh = fopen('pics/data.dat', 'w') or die("can't open file: $php_errormsg");
fwrite($fh, $imgfile."\n");
fwrite($fh, $text."\n");
fwrite($fh, $date."\n");
fwrite($fh, $mode."\n");
fwrite($fh, $dropshadow."\n");
fwrite($fh, $border."\n");
fwrite($fh, $picture."\n");
fwrite($fh, $font_size."\n");
fwrite($fh, $xpos."\n");
fwrite($fh, $ypos."\n");
fwrite($fh, $xposoff."\n");
fwrite($fh, $yposoff."\n");

//Write Color Information
fwrite($fh, $bgcolor."\n");
fwrite($fh, $txtcolor."\n");
fwrite($fh, $shadowcolor."\n");
fwrite($fh, $bordercolor."\n");
fclose($fh);
?>
<?php
$location = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$location = str_replace("process", "htmlcode", $location);
$location = "http://".$location;
?>
<script = type="text/javascript">
<!--
//window.location = "<?php echo $location; ?>"
-->
</script>

<a href="htmlcode.php">Click here</a> if you are not redirected in 5 seconds.
</body>
</html>