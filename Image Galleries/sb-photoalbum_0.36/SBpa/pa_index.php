<?php
/*
******************************************************************************************
** SB|photoAlbum                                                                        **
** Copyright (C)2005 Ladislav Soukup                                                    **
**                                                                                      **
** Tento program je svobodný software; mùete jej íøit a modifikovat podle             **
** ustanovení GNU General Public License, vydávané Free Software                        **
** Foundation; a to buï verze 2 této licence anebo (podle vaeho uváení)               **
** kterékoli pozdìjí verze.                                                            **
**                                                                                      **
** Tento program je roziøován v nadìji, e bude uiteèný, avak BEZ                    **
** JAKÉKOLI ZÁRUKY; neposkytují se ani odvozené záruky PRODEJNOSTI anebo                **
** VHODNOSTI PRO URÈITÝ ÚÈEL. Dalí podrobnosti hledejte ve GNU General Public License. **
**                                                                                      **
** Kopii GNU General Public License jste mìl obdret spolu s tímto                      **
** programem; pokud se tak nestalo, napite o ni Free Software Foundation,              **
** Inc., 675 Mass Ave, Cambridge, MA 02139, USA.                                        **
**                                                                                      **
** Autor:  Ladislav Soukup                                                              **
** e-mail: root@soundboss.cz                                                            **
** URL: http://php.soundboss.cz                                                         **
** URL: http://www.soundboss.cz                                                         **
******************************************************************************************
*/
if (function_exists("scan_dir")) { define("USE_PHP4", false); } else { define("USE_PHP4", true); } // PHP5 check.
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
$palogo_size = @getimagesize(pa_logo);
if ($palogo_size[0] < pa_leftframe_min_width) $palogo_size[0] = pa_leftframe_min_width;
if ($palogo_size[1] < 1) $palogo_size[1] = 1;
$pa_start_image = $pa_core->ImageDirectLink();
if (empty($pa_start_image)) {
	$pa_start_image = $pa_core->ImageOfDay();
}
define("pa_header_include_subdir", true);
include_once "./photoalbum/html_header.php";
?>
<body onresize="wnd_resize()">
<div id="loading"><?php echo pa_txt_loading; ?></div>
<div id="logo" style="display: none;"><?php if(!empty($palogo_size[3])){echo "<img id=\"img-logo\" src=\"".pa_logo."\" ".$palogo_size[3]." alt=\"\" />";}else{ echo " ";}?></div>
<div id="toolbar" style="display: none;"><!--TOOLBAR-->
<div id="toolbar_mode_1">
<div class="toolbar_btn" onclick="WndImgInfo()"><?php echo pa_txt_imginfo; ?></div>
<div class="toolbar_btn" onclick="OpenFullScreen()"><?php echo pa_txt_fullscreen; ?></div>
<div class="toolbar_btn" onclick="OpenWindow('slideshow',500,200)"><?php echo pa_txt_slideshow; ?></div>
<div class="toolbar_btn" onclick="OpenWindow('lang',400,300)"><?php echo pa_txt_lang; ?></div>
<div class="toolbar_btn" onclick="OpenWindow('about',250,150)"><?php echo pa_txt_about; ?></div>
</div>
<div id="toolbar_mode_2" class="toolbar_text">
<?php echo pa_txt_slideshow; ?>&nbsp;&nbsp;&nbsp;
<div class="toolbar_btn" onclick="OpenFullScreen()"><?php echo pa_txt_fullscreen; ?></div>
<div class="toolbar_btn" onclick="SlideShow_stop()"><?php echo pa_txt_slideshow_stop; ?></div>
</div>
&nbsp;<!--TOOLBAR **END**--></div>
<div id="image" style="display: none;">--IMAGE--</div>
<div id="thumbs" align="center" style="display: none;">--THUMBS--</div>
<div id="thumbs_prev" class="button" onclick="pa_thumbs_prev()" style="display: none;">&laquo;<br />&laquo;<br />&laquo;<br />&laquo;</div>
<div id="thumbs_next" class="button" onclick="pa_thumbs_next()" style="display: none;">&raquo;<br />&raquo;<br />&raquo;<br />&raquo;</div>
<div id="image_prev" class="button" onclick="pa_image_prev()" style="display: none;">&laquo;</div>
<div id="image_next" class="button" onclick="pa_image_next()" style="display: none;">&raquo;</div>
<div id="browser" style="display: none;">
<script src="photoalbum/dtree.js" type="text/javascript"></script>
<script type="text/javascript">
// TreeMenu INIT
var painit_dir_id = 1;
var painit_dir = '<?php echo $pa_start_image[0]; ?>';
var painit_image = '<?php echo $pa_start_image[1]; ?>';
pa_menu = new dTree('pa_menu');
pa_menu.config.useCookies=false;
pa_menu.add(0,-1,'<?php echo pa_title; ?>');
<?php
if (file_exists(pa_dir_tree_cache_file)) {
	// load DIR TREE from cache
	echo $pa_core->make_tree_from_cache(pa_dir_tree_cache_file);
} else {
	// create DIR TREE  - IT IS NOT CACHED !
	echo $pa_core->make_tree(pa_image_dir);
}
?>
document.write(pa_menu);
// MAIN INIT
var painit_logo_width = <?php echo $palogo_size[0]; ?>;
var painit_logo_height = <?php echo $palogo_size[1]; ?>;
var painit_image_w = <?php echo $pa_start_image[2]; ?>;
var painit_image_h = <?php echo $pa_start_image[3]; ?>;
var painit_thumb_size = <?php echo pa_image_show_thumb_size; ?>;
var pa_lang_code = '<?php echo used_lang_code; ?>';
var slideshow_time = '<?php echo pa_slideshow_timer; ?>';
var pa_txt_loading_dir = '<?php echo pa_txt_loading_dir; ?>';
var pa_txt_no_images_in_dir = '<?php echo pa_txt_no_images_in_dir; ?>';
var win = null;
</script>
</div>
<script src="photoalbum/core.js" type="text/javascript"></script>
<!-- SB|photoAlbum - version: <?php echo $pa_core->version; ?> -->
</body>
</html>