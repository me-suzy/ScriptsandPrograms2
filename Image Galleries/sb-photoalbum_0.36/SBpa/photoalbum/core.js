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
// XML RCP  INIT
if (window.XMLHttpRequest){
	// Mozilla
	var xmlhttp = new XMLHttpRequest();
} else if (window.ActiveXObject){
	// IE
	var xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}
// MAIN FUNCTIONs
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

function wnd_resize() {
	GetWindowSize();
	left_frame_width = painit_logo_width;
	logo_height = painit_logo_height;
	thumb_size = painit_thumb_size;
	thumb_frame_height = thumb_size + 20;
	thumb_button_next_prev_height = 50;
	thumb_button_next_prev_padding = Math.round((thumb_frame_height - thumb_button_next_prev_height) / 2);
	thumbs_scroll_div_width = 15;
	image_scroll_div_width = 15;
	image_scroll_div_height = 15;
	toolbar_height = 20;
	page_width = page_width - 5;
	page_height = page_height - 5;
	image_max_height = page_height - thumb_frame_height - toolbar_height;
	image_max_width = page_width - left_frame_width;
	thumbs_div_width = page_width - left_frame_width;
	
	document.getElementById('logo').style.display = 'block';
	document.getElementById('logo').style.position = 'absolute';
	document.getElementById('logo').style.top = '0px';
	document.getElementById('logo').style.left = '0px';
	document.getElementById('logo').style.width = left_frame_width + 'px';
	document.getElementById('logo').style.height = logo_height + 'px';
	
	document.getElementById('toolbar').style.display = 'block';
	document.getElementById('toolbar').style.position = 'absolute';
	document.getElementById('toolbar').style.top = '0px';
	document.getElementById('toolbar').style.left = (left_frame_width) + 'px';
	document.getElementById('toolbar').style.width = image_max_width + 'px';
	document.getElementById('toolbar').style.height = toolbar_height + 'px';
	
	document.getElementById('browser').style.display = 'block';
	document.getElementById('browser').style.position = 'absolute';
	document.getElementById('browser').style.top = (logo_height) + 'px';
	document.getElementById('browser').style.left = '0px';
	document.getElementById('browser').style.height = (page_height - logo_height) + 'px';
	document.getElementById('browser').style.width = left_frame_width  + 'px';
	
	document.getElementById('image').style.display = 'block';
	document.getElementById('image').style.position = 'absolute';
	document.getElementById('image').style.top = (toolbar_height) + 'px';
	document.getElementById('image').style.left = (left_frame_width) + 'px';
	document.getElementById('image').style.height = image_max_height + 'px';
	document.getElementById('image').style.width = image_max_width + 'px';
	
	document.getElementById('thumbs').style.display = 'block';
	document.getElementById('thumbs').style.position = 'absolute';
	document.getElementById('thumbs').style.top = (page_height - thumb_frame_height) + 'px';
	document.getElementById('thumbs').style.left = (left_frame_width) + 'px';
	document.getElementById('thumbs').style.height = (thumb_frame_height) + 'px';
	document.getElementById('thumbs').style.width = thumbs_div_width  + 'px';
	
	document.getElementById('thumbs_prev').style.display = 'block';
	document.getElementById('thumbs_prev').style.position = 'absolute';
	document.getElementById('thumbs_prev').style.padding = thumb_button_next_prev_padding + 'px 0px';
	document.getElementById('thumbs_prev').style.top = (page_height - thumb_frame_height) + 'px';
	document.getElementById('thumbs_prev').style.left = (left_frame_width) + 'px';
	document.getElementById('thumbs_prev').style.height = (thumb_frame_height) + 'px';
	document.getElementById('thumbs_prev').style.width = thumbs_scroll_div_width  + 'px';
	
	document.getElementById('thumbs_next').style.display = 'block';
	document.getElementById('thumbs_next').style.position = 'absolute';
	document.getElementById('thumbs_next').style.padding = thumb_button_next_prev_padding + 'px 0px';
	document.getElementById('thumbs_next').style.top = (page_height - thumb_frame_height) + 'px';
	document.getElementById('thumbs_next').style.left = (page_width - thumbs_scroll_div_width) + 'px';
	document.getElementById('thumbs_next').style.height = (thumb_frame_height) + 'px';
	document.getElementById('thumbs_next').style.width = thumbs_scroll_div_width  + 'px';
	
	document.getElementById('image_prev').style.display = 'block';
	document.getElementById('image_prev').style.position = 'absolute';
	document.getElementById('image_prev').style.top = Math.round((image_max_height / 2) + toolbar_height + (image_scroll_div_height / 2)) + 'px';
	document.getElementById('image_prev').style.left = (left_frame_width) + 'px';
	document.getElementById('image_prev').style.height = (image_scroll_div_height) + 'px';
	document.getElementById('image_prev').style.width = (image_scroll_div_width) + 'px';
	
	document.getElementById('image_next').style.display = 'block';
	document.getElementById('image_next').style.position = 'absolute';
	document.getElementById('image_next').style.top = Math.round((image_max_height / 2) + toolbar_height + (image_scroll_div_height / 2)) + 'px';
	document.getElementById('image_next').style.left = (page_width - image_scroll_div_width) + 'px';
	document.getElementById('image_next').style.height = (image_scroll_div_height) + 'px';
	document.getElementById('image_next').style.width = (image_scroll_div_width) + 'px';
	
	NumOfThumbs = thumbs_div_width / thumb_size;
	NumOfThumbs = Math.round((thumbs_div_width - (NumOfThumbs * 10) - 60) / thumb_size);
	
	pa_redraw_thumbs();
	if (ImageID >= 0){
		ShowImage(ImageID);
	}
}

function ShowImage(id){
	if (images_array[id] != ""){
		if (images_width_array[id] > image_max_width){
			ratio = image_max_width / images_width_array[id];
			width = image_max_width;
			height = images_height_array[id] * ratio;
		} else {
			width = images_width_array[id];
			height = images_height_array[id];
		}
		if (images_height_array[id] > image_max_height){
			ratio = image_max_height / height;
			if ((width * ratio) <= image_max_width){
				height = image_max_height;
				width = width * ratio;
			}
		}
		padding_left = Math.round((image_max_width - width) / 2);
		padding_top = Math.round((image_max_height - height) / 2);
		image_html = '<img src="photoalbum/gd-image.php?dir='+image_dir+'&img='+images_array[id]+'" alt="IMAGE '+id+'" width="'+width+'" height="'+height+'" style="padding-left: '+padding_left+'px; padding-top: '+padding_top+'px;" />';
		document.getElementById('image').innerHTML = image_html;
		thumb_id = 'thumb-' + id;
		// Mark active thumb
		
		ImageID = id;
		if (FullScreen_opened) {
			if (win.FullScreenShowImage) {
				win.FullScreenShowImage();
				setTimeout("win.focus()",500);
			} else {
				FullScreen_opened = false;
			}
		}
		
		if ((ImageID - 1) >= 0) { document.getElementById('image_prev').style.display = 'block'; } else { document.getElementById('image_prev').style.display = 'none'; }
		if ((ImageID + 1) < TotalImages) {
			document.getElementById('image_next').style.display = 'block';
			// Preload Next image
			ImagePreLoad="";
			ImagePreLoad = new Image();
			ImagePreLoad.src = 'photoalbum/gd-image.php?dir='+image_dir+'&img='+images_array[(ImageID+1)];
		} else {
			document.getElementById('image_next').style.display = 'none';
		}
	}
}

function pa_image_next() {
	if ((ImageID + 1) <= TotalImages) {
		ImageID = ImageID + 1;
		ShowImage(ImageID);
		pa_thumbs_align_to_image()
	} else {
		SlideShow_stop();
	}
}

function pa_image_prev() {
	if ((ImageID - 1) >= 0) {
		ImageID = ImageID - 1;
		ShowImage(ImageID);
		pa_thumbs_align_to_image()
	} else {
		SlideShow_stop()
	}
}

function pa_thumbs_align_to_image() {
	if (ImageID < FirstThumb) {
		FirstThumb = ImageID - NumOfThumbs + 1;
		if (FirstThumb < 0) { FirstThumb = 0; }
		pa_redraw_thumbs();
	}
	if (ImageID >= (FirstThumb + NumOfThumbs)) {
		FirstThumb = ImageID;
		pa_redraw_thumbs();
	}
}

function pa_thumbs_next() {
	// NumOfThumbs  FirstThumb  TotalImages
	FirstThumb = FirstThumb + NumOfThumbs;
	pa_redraw_thumbs();
}

function pa_thumbs_prev() {
	FirstThumb = FirstThumb - NumOfThumbs;
	if (FirstThumb < 0) FirstThumb = 0;
	pa_redraw_thumbs();
}

function pa_redraw_thumbs() {
	thumbs_html = "";
	for(loop=0; loop<NumOfThumbs; loop++){
		thumb_id = FirstThumb + loop
		if (thumb_id >= TotalImages) {
			thumb_image = 'photoalbum/img/thumb.gif';
			thumb_link = 'return false';
			thumb_class = 'thumb_no';
		} else {
			thumb_image = 'photoalbum/gd-thumb.php?dir='+image_dir+'&img='+images_array[thumb_id];
			thumb_link = 'ShowImage('+thumb_id+'); SlideShow_stop();';
			thumb_class = 'thumb';
		}
		thumbs_html = thumbs_html + '<img id="thumb-'+thumb_id+'" class="'+thumb_class+'" src="'+thumb_image+'" width="'+thumb_size+'" height="'+thumb_size+'" alt="t'+thumb_id+'" onclick="'+thumb_link+'" />';
	}
	document.getElementById('thumbs').innerHTML = thumbs_html;
	if ((FirstThumb + NumOfThumbs) < TotalImages) {
		document.getElementById('thumbs_next').style.display = 'block';
	} else {
		document.getElementById('thumbs_next').style.display = 'none';
	}
	if (FirstThumb > 0) {
		document.getElementById('thumbs_prev').style.display = 'block';
	} else {
		document.getElementById('thumbs_prev').style.display = 'none';
	}
	
}

function pa_chdir(dir, menu_id, ImageReDraw) {
	pa_menu.openTo(menu_id, true);
	if (allowAction == true) {
		allowAction = false;
		SlideShow_stop();
		document.getElementById('image').innerHTML = '<div id="loading_dir">'+pa_txt_loading_dir+'</div>';
		xmlhttp.open("GET", 'photoalbum/rpc-getfiles.php?dir='+dir, true);
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4) {
				// Do action...
				//alert (xmlhttp.responseText);
				images_array = Array();
				images_width_array = Array();
				images_height_array = Array();
				FirstThumb = 0;
				ImageID = 0;
				data_array = xmlhttp.responseText.split("\n");
				for (var i = 0; i < data_array.length; i++) {
					data = data_array[i].split(";");
					images_array[i] = data[0];
					images_width_array[i] = data[1];
					images_height_array[i] = data[2];
					if ((painit_image != "") && (painit_image == data[0])){
						ImageID = i;
						FirstThumb = i;
						painit_image = ""; //Clear InitSettings
					}
				}
				image_dir = dir;
				TotalImages = (images_array.length - 1);
				if (TotalImages < 1) {
					document.getElementById('image').innerHTML = '<div id="loading_dir">'+pa_txt_no_images_in_dir+'</div>';
				}
				// DATA OK.
				pa_redraw_thumbs();
				if (ImageReDraw) { ShowImage(ImageID); }
				allowAction = true;
			}
		}
		xmlhttp.send(null);
	}
}

function WndImgInfo(){
	pa_wnd_params = '&dir=' + image_dir + '&img=' + images_array[ImageID];
	OpenWindow('imginfo', 600, 400);
	pa_wnd_params = '';
}

function OpenFullScreen() {
	OpenWindow('fullscreen', screen.width, screen.height);
}

function SlideShow_stop() {
	if (slideshow_enable) {
		slideshow_enable = false;
		if (win) { win.close(); FullScreen_opened = false; }
		ToolBarMode(1);
	}
}

function SlideShow_start() {
	if (win) { win.close();	}
	if (slideshow_enable == false) {
		slideshow_enable = true;
		ToolBarMode(2);
		setTimeout("SlideShow_step()", (200));
	}
}

function SlideShow_step() {
	if (slideshow_enable) {
		if (slideshow_fullscreen && !FullScreen_opened) { OpenFullScreen(); }
		if (ImagePreLoad.complete) {
			pa_image_next();
			setTimeout("SlideShow_step()", (slideshow_time * 1000));
		} else {
			setTimeout("SlideShow_step()",250);
		}
	}
}

function ToolBarMode(t_mode) {
	document.getElementById('toolbar_mode_1').style.display = 'none';
	document.getElementById('toolbar_mode_2').style.display = 'none';
	t_mode_id = "toolbar_mode_" + t_mode;
	document.getElementById(t_mode_id).style.display = 'inline';
}

// foreach
// for (var i = 0;i<array.length;i++){ alert(array[i]; }
//

// ********* //
// MAIN INIT //
// ********* //
var allowAction = true;
var FullScreen_opened = false;
var FullScreen_do_reload = false;
var slideshow_enable = false;
var slideshow_fullscreen = false;
var page_width = 1024;
var page_height = 768;
var NumOfThumbs = 5;
var FirstThumb = 0;
var TotalImages = 0;
var image_max_height = 0;
var image_max_width = 0;
var ImageID = 0;
var ImagePreLoad = new Image(); 
var images_array = Array();
var images_width_array = Array();
var images_height_array = Array();
var image_dir = painit_dir;
// INIT Image
images_array[0] = painit_image;
images_width_array[0] = painit_image_w;
images_height_array[0] = painit_image_h;
pa_chdir(painit_dir, painit_dir_id, true);
ToolBarMode(1);
wnd_resize(); // INIT LAYOUT
document.getElementById('loading').style.display = 'none';
