<?php
if (file_exists("install.php")) {
	echo "You must delete install.php";
	exit();
}
require('config.php');
require_once('functions.php');
if (isset($_GET['action']) && $_GET['action'] == "submit") {
	$result = add_plug($_POST['url'],$_POST['image'],$_POST['ip']);
	if ($result != 1) {
		echo "$result<br />\n<a href=\"dsplugger.php\">Back</a>";
		exit();
	}
	header("Location: dsplugger.php");
}
if (function_exists('imagecreate')) {
	$test = image_create();
	if ($test) {
		$setting = get_params("dsp_settings");
		$image = "image.php?size={$setting['width']}x{$setting['height']}";
	} else {
		$image = "image.php?size=1";
	}
} else {
	$image = "image.php?size=1";
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="author" content="Chris Warren" />
<meta name="copyright" content="2005 Chris Warren" />
<title>DS Plugger</title>
<script type="text/javascript">
function failsafeImg(){
  var badImg = new Image();
  badImg.src = '<?php echo $image; ?>';
  for(var i=0;i<document.images.length;i++){
    var cpyImg = new Image();
    cpyImg.src = document.images[i].src;
    if(!cpyImg.width){
      document.images[i].src = badImg.src;
    }
  }
}
onload = failsafeImg;
</script>
<style type="text/css">
<?php
get_style(); ?>
img {
	border: 0;
}
<?php
$setting = get_params("dsp_settings");
$height = $setting['height'] + 4;
$total_height = $height * $setting['rows'];
$width = $setting['width'] + 3;
$total_width = $width * $setting['columns'];
?>
#container {
	width: <?php echo $total_width; ?>px;
}
#plugs {
	height: <?php echo $total_height; ?>px;
	width: <?php echo $total_width; ?>px;
}
</style>
</head>
<body style="margin: 0">
<div id="container">
<div id="plugs">
<?php
$plugs = get_plugs("limit");
$setting = get_params("dsp_settings");
if ($plugs) {
	$n = 1;
	foreach ($plugs as $key=>$value) { ?>
<a target="<?php echo $setting['target']; ?>" href="<?php echo $value['url']; ?>"><img src="<?php echo $value['image']; ?>" width="<?php echo $setting['width']; ?>px" height="<?php echo $setting['height']; ?>px" alt="<?php echo $value['url']; ?>" /></a>
<?php
		if ($setting['columns'] == $n) {
			echo "<br />\n";
			$n = 1;
		} else { 
			$n++;
		}
	}
}
?>
</div>
<div align="center">
<div id="form">
<form action="?action=submit" method="post">
URL: &nbsp;&nbsp;&nbsp;<input type="text" name="url" /><br />
Image: <input type="text" name="image" /><br />
<input type="hidden" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" />
<input type="submit" name="submit" value="Submit" /> <a target="_blank" href="admin.php">Admin</a><br />
Powered by <a target="_blank" href="http://www.dawgiestyle.com">DS Plugger</a>
</form>
</div></div></div>
</body>
</html>