<?
include "./config.php";
$v = @cleanup();

$urlarray = explode("/", $REQUEST_URI);
$slashcnt = count($urlarray);
$id = $urlarray[$slashcnt - 2];

if (!$id) exit;
$sql = "select owner,filename,views from $tablepics where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
if ($numrows==0) exit;
$resrow = mysql_fetch_row($result);
$owner = $resrow[0];
$filename = $resrow[1];
$views = $resrow[2];
$views++;
$sql = "update $tablepics set views='$views' where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$sql = "select totalviews from $tableusers where id='$owner'";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
if ($numrows==0) exit;
$resrow = mysql_fetch_row($result);
$totalviews = $resrow[0];
$totalviews++;
$sql = "update $tableusers set totalviews='$totalviews' where id='$owner'";
$result = mysql_query($sql) or die("Failed: $sql");


$fullpath = $upfilesfolder.$owner."-".$filename;


$imgtyp = substr($filename, -3);

$imgtyp = strtolower($imgtyp);
if ($imgtyp=="jpg") Header("Content-Type: image/jpeg");
if ($imgtyp=="gif") Header("Content-Type: image/gif");
if ($imgtyp=="png") Header("Content-Type: image/png");

if ($imgtyp=="jpg") $im = ImageCreateFromJPEG($fullpath);
if ($imgtyp=="gif") $im = ImageCreateFromGIF($fullpath);
if ($imgtyp=="png") $im = ImageCreateFromPNG($fullpath);

$text_colors=explode(" ", $overlay_colors);
$text_color = ImageColorAllocate($im, $text_colors['0'], $text_colors['1'], $text_colors['2']);
$font_height = ImageFontHeight(3);
$font_width = ImageFontWidth(3);
$image_height = ImageSY($im);
$image_width = ImageSX($im);
$length = $font_width * strlen($overlay_text);
if(empty($overlay_x)){
	$image_center_x = ($image_width/2)-($length/2);
}else{
	$image_center_x = $overlay_x;
}
if(empty($overlay_y)){
	$image_center_y = ($image_height/2)-($font_height/2);
}else{
	$image_center_y = $overlay_y;
}

ImageString($im, $overlay_font, $image_center_x, $image_center_y, $overlay_text, $text_color);

ImageJPEG($im);
?>