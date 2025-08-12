<?php
include 'clsMetaContent.php';

function myheader($ptitle){


$meta = &new Meta;
$meta->company_name = "TRIANGLE WEB GAMES";
$meta->description = "TRIANGLE'S SIGNATURE INTERACTIVE GRAPHIC NOVEL";
$meta->keywords2 = "SIGN, SIGNB, SIGNR";
$meta->sitename = "TRIANGLE'S SIGN";
$meta->slogan = "DON'T BE SQUARE, SIT BACK AND PLAY YOUR GAMES RIGHT THERE";

?>
<HTML>
<HEAD>
<?php echo $meta->metadata($ptitle); ?>
</HEAD>
<BODY>
<?php
}//closes myheader
?>