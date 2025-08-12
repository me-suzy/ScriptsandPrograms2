<?php
/**
* croptool.php
*
* This file prints the cropping interface for both fullsize and thumbnail
* images when using the cropping tool.
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/

/**
*
* {@source }
*/
if ((isset($_REQUEST['croptype'])) && ($_REQUEST['croptype']=="full")) {
	$max_width = $img_width;
	$max_height = $img_height;
	$button_label = $LANG_ADMIN_LABEL_SET_CROP;
} else {
	$max_width = $cfg_maxthumb_width;
	$max_height = $cfg_maxthumb_height;
	$button_label = $LANG_ADMIN_LABEL_SET_THUMB;
}
?>

<div id="theCrop" style="position:absolute;background-color:transparent;border:1px solid yellow;width:<?php echo $max_width; ?>px;height:<?php echo $max_height; ?>px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $cfg_app_url; ?>/images/transbg.png',sizingMethod='scale');"></div>
<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
<tr><td align="center" class="resultline-alt"><input type="radio" id="resizeAny" name="resize" onClick="my_SetResizingType(0);" checked> <label for="resizeAny"><?php echo $LANG_ADMIN_CROPTOOL_ANY; ?></label> &nbsp; <input type="radio" name="resize" id="resizeProp" onClick="my_SetResizingType(1);"> <label for="resizeProp"><?php echo $LANG_ADMIN_CROPTOOL_PROPORT; ?></label></td></tr>
<tr><td class="resultline-alt" align="center"><img src="<?php echo $cfg_pics_url."/".$image_filename."?".date("U"); ?>" width="<?php echo $img_width; ?>" height="<?php echo $img_height; ?>" alt="crop this image" name="theImage"></td></tr>

<tr><td class="resultline" align="right"><div align="right"><input type="submit" value="<?php echo $button_label; ?>"  class="formbutton"  id="submit" onClick="my_Submit();"></div></td></tr>
</table>
</center>

<p><?php echo $LANG_ADMIN_CROPTOOL_NOTE; ?></p>