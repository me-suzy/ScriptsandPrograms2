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

<img id="fullscreen_image" onclick="opener.slideshow_fullscreen=false; self.close();" src="" alt="<?php echo pa_txt_fullscreen; ?>" />
<div id="fullscreen_prev" class="button" style="padding: 2px; font-weight: bold; position: absolute; left: 5px; cursor: pointer;" onclick="opener.pa_image_prev()">&laquo;</div>
<div id="fullscreen_next" class="button" style="padding: 2px; font-weight: bold; position: absolute; right: 10px; cursor: pointer;" onclick="opener.pa_image_next()">&raquo;</div>

<script language="JavaScript" type="text/javascript">
function GetWindowSize() {
	if( typeof( window.innerWidth ) == 'number' ) {
		//Non-IE
		page_width = window.innerWidth;
		page_height = window.innerHeight;
	} else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
		//IE 6+ in 'standards compliant mode'
		page_width = document.documentElement.clientWidth;
		page_height = document.documentElement.clientHeight;
	} else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
		//IE 4 compatible
		page_width = document.body.clientWidth;
		page_height = document.body.clientHeight;
	}
}

function FullScreenShowImage() {
	fulscreen_image_src = 'gd-image.php?dir='+opener.image_dir+'&img='+opener.images_array[opener.ImageID];
	if (opener.images_width_array[opener.ImageID] > image_max_width){
		ratio = image_max_width / opener.images_width_array[opener.ImageID];
		width = image_max_width;
		height = opener.images_height_array[opener.ImageID] * ratio;
	} else {
		width = opener.images_width_array[opener.ImageID];
		height = opener.images_height_array[opener.ImageID];
	}
	if (opener.images_height_array[opener.ImageID] > image_max_height){
		ratio = image_max_height / height;
		if ((width * ratio) <= image_max_width){
			height = image_max_height;
			width = width * ratio;
		}
	}
	padding_left = Math.round((image_max_width - width) / 2);
	padding_top = Math.round((image_max_height - height) / 2);
	fullscreen_pading = padding_top + 'px 0px 0px ' + padding_left + 'px';
	document.getElementById('fullscreen_image').src = fulscreen_image_src;
	document.getElementById('fullscreen_image').style.width = width+'px';
	document.getElementById('fullscreen_image').style.height = height+'px';
	document.getElementById('fullscreen_image').style.padding = fullscreen_pading;
}

opener.FullScreen_opened = true;
GetWindowSize();

document.getElementById('fullscreen_prev').style.top = (page_height/2) + 'px';
document.getElementById('fullscreen_next').style.top = (page_height/2) + 'px';

image_max_width = page_width;
image_max_height = page_height;
FullScreenShowImage();
</script>
</body>
</html>