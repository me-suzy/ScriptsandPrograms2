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
chdir("..");
include_once "./pa_config.php";
include_once "./photoalbum/core.php";
$pa_core = new pa_core();
define("pa_header_include_subdir", false);
include_once "./photoalbum/html_header.php";
?>
<body>
<h1 id="img_name"><?php echo pa_txt_slideshow; ?></h1>
<div class="hline">&nbsp;</div>
<?php if (!pa_slideshow_enabled) { ?>
<div style="text-align: center;"><?php echo pa_txt_slideshow_is_disabled; ?></div>
<?php } else { ?>
<div>
<span style="font-weight: bold;"><?php echo pa_txt_slideshow_time; ?>:</span>
<?php
foreach(unserialize(pa_slideshow_times) as $time) {
	echo "<input type=\"radio\" id=\"wnd_slideshow_time-".$time."\" name=\"wnd_slideshow_time\" value=\"".$time."\" onclick=\"opener.slideshow_time = ".$time."\" />".$time."\n";
}
?>
</div><div style="font-size: 10px; font-style: italic;">*&nbsp;<?php echo pa_txt_slideshow_time_depend_on_conn_speed; ?></div>
<div style="margin-top: 10px;">
<span style="font-weight: bold;"><?php echo pa_txt_slideshow_fullscreen; ?>:</span>
<input type="radio" id="wnd_slideshow_fullscreen-true" name="wnd_slideshow_fullscreen" onclick="opener.slideshow_fullscreen=true" value="true" /><?php echo pa_txt_yes; ?>
&nbsp;&nbsp;&nbsp;
<input type="radio" id="wnd_slideshow_fullscreen-false" name="wnd_slideshow_fullscreen" onclick="opener.slideshow_fullscreen=false" value="false" /><?php echo pa_txt_no; ?>
</div>
<br />

<div style="text-align: center; font-weight: bold; text-transform: capitalize;">
<span class="toolbar_btn" onclick="opener.SlideShow_start()"><?php echo pa_txt_slideshow_start; ?></span>
&nbsp;&nbsp;&nbsp;
<span class="toolbar_btn" onclick="opener.SlideShow_stop()"><?php echo pa_txt_slideshow_stop; ?></span>
</div><br />
<script language="JavaScript" type="text/javascript">
	tempID = 'wnd_slideshow_time-' + opener.slideshow_time;
	document.getElementById(tempID).checked = true;
	tempID = 'wnd_slideshow_fullscreen-' + opener.slideshow_fullscreen;
	document.getElementById(tempID).checked = true;
</script>
<?php } ?>
<div style="padding-top: 10px; text-align: center;"><div class="toolbar_btn" onclick="self.close()"><?php echo pa_txt_close_window; ?></div></div>
</body>
</html>