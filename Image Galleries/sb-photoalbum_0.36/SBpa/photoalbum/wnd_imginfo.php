<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
if (strstr($_GET["dir"], "..")) die();
if (strstr($_GET["img"], "..")) die();
chdir("..");
include_once "./pa_config.php";
define("pa_header_include_subdir", false);
include_once "./photoalbum/html_header.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
$img_info = $pa_core->parseImgInfo($_GET["dir"], $_GET["img"]);
$img_exif = $pa_core->parseImgExif($_GET["dir"], $_GET["img"]);
$image_url = $pa_core->URLStripLastDir(pa_home_url) . "pa_index.php?folder=" . $_GET["dir"] . "&amp;image=" . $_GET["img"];
?>
<body>
<h1 id="img_name"><?php echo $_GET["img"]; ?></h1>
<div class="hline">&nbsp;</div>
<div style="padding-top: 5px;"><?php echo $img_info["text"]; ?></div>
<div style="padding-top: 10px;">
<span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_author; ?>:</span> <?php echo $img_info["author"]; ?><br />
<span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_date; ?>:</span> <?php echo $img_info["date"]; ?><br />
<span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_file_size; ?>:</span> <?php echo $img_info["filesize"]; ?><br />
<span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_resultion; ?>:</span> <?php echo $img_info["imageresultion"]; ?><br />
<?php if (!empty($img_exif["Model"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_camera; ?>:</span> <?php echo $img_exif["Make"] . " " . $img_exif["Model"] ?><br /><?php } ?>
<?php if (!empty($img_exif["ExposureTime"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_exposure_time; ?>:</span> <?php echo $img_exif["ExposureTime"] ?><br /><?php } ?>
<?php if (!empty($img_exif["FNumber"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_fnumber; ?>:</span> <?php echo $img_exif["FNumber"] ?><br /><?php } ?>
<?php if (!empty($img_exif["FocalLength"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_focal_length; ?>:</span> <?php echo $img_exif["FocalLength"] ?><br /><?php } ?>
<?php if (!empty($img_exif["ISOSpeedRatings"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_iso; ?>:</span> <?php echo $img_exif["ISOSpeedRatings"] ?><br /><?php } ?>
<?php if (!empty($img_exif["Flash"])) { ?><span style="font-weight: bold; text-transform: capitalize;"><?php echo pa_txt_image_exif_flash; ?>:</span> <?php if ($img_exif["Flash"] > 0) { echo pa_txt_yes; } else { echo pa_txt_no; } ?><br /><?php } ?>
</div>
<br /><div class="hline">&nbsp;</div>
<div style="font-weight: bold;"><?php echo pa_txt_image_link; ?>:</div>
<div style="font-size: 9px; margin: 5px;"><?php echo $image_url; ?></div>
<div style="padding-top: 10px; text-align: center;"><div class="toolbar_btn" onclick="self.close()"><?php echo pa_txt_close_window; ?></div></div>
<script language="JavaScript" type="text/javascript">
if (document.getElementById('img_name').innerHTML == "undefined"){
	self.close();
}
</script>
</body>
</html>