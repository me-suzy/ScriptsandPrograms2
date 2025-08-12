<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

//------------------------
// image_mod.php for pivot 
// by paul@mijnkopthee.nl 
// last update: 04/05/03
//------------------------

// pv_core must be included
if (file_exists("pv_core.php")) {
	include_once('pv_core.php');
} else {
	chdir('..');
	include_once('pv_core.php');
}

include_once("modules/module_imagefunctions.php");

global $Pivot_Vars;


chdir('..');
chdir($Cfg['upload_path']);


// -- main --

if(!$img) {
	$img =  $Pivot_Vars['image'];
}

// get original image attributes
$attr = get_image_attributes( $img );	
$img = new Attributes($attr['name'],$attr['w'],$attr['h'],$attr['x'],$attr['y']);


if(isset($Pivot_Vars['crop'])) {
	// create the thumbnail!
	create_thumbnail();		
} else {
	// show the JS crop editor!
	print_crop_editor();									
}


// -- main --


// Nothing to change from here
// -------------------------------
function get_image_attributes($img) {

	
	if(!file_exists($img)) { 
		$img=stripslashes(urldecode($img)); 
	}

	if(!file_exists($img)) {
		echo "<br />$img can not be opened. <br />";
		echo "Current Path: ".getcwd()."<br />";
		die();
	}
	
	$nfo = getImageSize($img);

	return Array('name'=>$img,'w'=>$nfo[0],'h'=>$nfo[1],'x'=>0,'y'=>0,'extra'=>$nfo);
}



function create_thumbnail()
{
	global $img, $Pivot_Vars;
		
	$thumb = new Image($Pivot_Vars['crop'],$Pivot_Vars['crop_w'],$Pivot_Vars['crop_h'],$Pivot_Vars['crop_x'],$Pivot_Vars['crop_y']);

	$ext = strtolower($img->ext);

	if( ($ext == 'gif') || ($ext == 'jpg') || ($ext == 'jpeg') || ($ext == 'png') ) {
		gd_crop($thumb);
	} else {
		echo "This file extension is not supported, please try JPG, GIF or PNG";
		print_module_footer();
	}
}



class Image
{
	var $name, $w, $h, $x, $y;

	function Image($n,$w,$h,$x,$y) 
	{
		$this->name	= $n;
		$this->w	= $w;
		$this->h	= $h;
		$this->x	= $x;
		$this->y	= $y;
	}	
}



class Attributes extends Image {
	
	var $ext, $new_name;

	function Attributes($n,$w,$h,$x,$y) 
	{
		$this->Image($n,$w,$h,$x,$y);
		
		if(preg_match("/([a-zA-Z]+)$/i",$n,$m)) {
			$this->ext = $m[0];
			$this->new_name = preg_replace("/(\.)(.*)$/i",".thumb.".$m[0],$n);
		} else {
			echo "Error on creating thumbnail $n";
			die();
		}
	}
}



function print_crop_editor()
{
	global $host, $img, $Cfg, $base_url, $images_dir, $mw, $mh, $Paths;

	$factor = max( ($img->w / 500) , ($img->h / 400) );

	$w = round($img->w/$factor);
	$h = round($img->h/$factor);
	
	if (($mw/$img->w) > ($mh/$img->h)) {
		// thumb stretches full width
		$def_x = 0;
		$def_w = $img->w/$factor - 2;

		$thumbfactor = ($mw / $def_w);

		$def_y = ( $img->h / $factor / 2 ) - ( $mh / $thumbfactor / 2 );
		$def_h = $mh / $thumbfactor;
		// thumb's y and height are centered horizontally on the middle of the image..

	} else {
		// thumb stretches full height
		$def_y = 0;
		$def_h = $img->h/$factor - 2;

		$thumbfactor = ($mh / $def_h);

		$def_x = ( $img->w / $factor / 2 ) - ( $mw / $thumbfactor / 2 );
		$def_w = $mw / $thumbfactor;
		// thumb's y and height are centered horizontally on the middle of the image..

	}
		
	$filename = dirname(dirname($Paths['pivot_url'])) . "/" . $Cfg['upload_path'] .  $img->name;
	$filename = fixpath( $filename);

	print_style();
?>
<div id="editor">
	<form name="holder" method="GET" onSubmit="return validate(<?php echo $factor; ?>);" action="module_image.php">
		<input type="hidden" name="image" value="<?php echo $img->name; ?>" />
		<input type="hidden" name="crop" value="<?php echo $img->new_name; ?>" />
		<input type="hidden" name="ext" value="<?php echo $img->ext; ?>" />
		<table border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td>x:<input name="crop_x" value='0' size="3" readonly="true" /></td>
				<td>y:<input name="crop_y" value='0' size="3" readonly="true" /></td>
				<td>w:<input name="crop_w" value='0' size="3" readonly="true" /></td>
				<td>h:<input name="crop_h" value='0' size="3" readonly="true" /></td>
				<td><input type="submit" value="create thumbnail!" class="button" /></td>
				</td>
			</tr>
			<tr>
				<td align="center" class='light'><small><a href="javascript:min_x(<?php echo $w; ?>);">min</a> / <a href="javascript:plus_x(<?php echo $w; ?>);">plus</a></small></td>
				<td align="center" class='light'><small><a href="javascript:min_y(<?php echo $w; ?>);">min</a> / <a href="javascript:plus_y(<?php echo $h; ?>);">plus</a></small></td>
				<td align="center" class='light'><small><a href="javascript:min_w(<?php echo $w; ?>);">min</a> / <a href="javascript:plus_w(<?php echo $w; ?>);">plus</a></small></td>
				<td align="center" class='light'><small><a href="javascript:min_h(<?php echo $h; ?>);">min</a> / <a href="javascript:plus_h(<?php echo $h; ?>);">plus</a></small></td>
				<td class='light'><small>increment: <a href="javascript:jfactor(1);">1px</a> - <a href="javascript:jfactor(10);">10px</a> - <a href="javascript:jfactor(50);">50px</a></small></td>
			</tr>
		</table>
	</form>
	<div id="super_holder" style="position:relative; z-index:0; border:none; width:<?php echo $w; ?>px; height:<?php echo $h; ?>px; top:0px; left:0px; padding:0; margin:10px 0 0 0;">
		<div id="img_holder" style="position:absolute; z-index:1; width:<?php echo $w; ?>px; height:<?php echo $h; ?>px; top:0; left:0; padding:0; margin:0;"></div>
		<div id="img_crop" style="position:absolute; z-index:2; border:1px solid yellow; width:<?php echo $def_w; ?>px; height:<?php echo $def_h; ?>px; top:<?php echo $def_y; ?>px; left:<?php echo $def_x; ?>px; padding:0; margin:0;">
			<img src="../pics/nix.gif" name="img" width="100%" height="100%" />
			<div id="slider_crop" style="position:absolute; z-index:3; border:none; width:10px; height:10px; bottom:-1px; right:-1px; padding:0; margin:0; background-color:yellow">
				<img src="../pics/nix.gif" name="slider" width="10" height="10" />
			</div>
		</div>
		<img src="<?php echo $filename; ?>" width="<?php echo $w; ?>" height="<?php echo $h; ?>" /><br />
	</div>
	<div id="clear"></div>
	<div class='overlay'>&rarr; Above image is shown at <strong><?php printf("%d%%",(1.0 / $factor) * 100); ?></strong> of the original size.</div>
	<div id="target">&nbsp;</div>
</div>


<script language="JavaScript" type="text/javascript" SRC="../includes/js/dhtmlapi.js"></script>
<script language="JavaScript" type="text/javascript" SRC="../includes/js/dhtmleditor.js"></script>
<script language="JavaScript">
<!--
	document.holder.crop_x.value = <?php echo round($def_x); ?>;
	document.holder.crop_y.value = <?php echo round($def_y); ?>;
	document.holder.crop_w.value = <?php echo round($def_w); ?>;
	document.holder.crop_h.value = <?php echo round($def_h); ?>;
	
	var maxWidth = <?php echo $mw; ?>;
	var maxHeight = <?php echo $mh; ?>;
	var factor = <?php echo $factor; ?>;

	target();		
	init();
//-->
</script>
<?php
}

function print_module_footer () {

	global $img;

	printf("<div class='overlay'>&rarr; Go <a href=\"module_image.php?image=%s\">back</a>, if the thumbnail is not satisfactory.</div>\n", $img->name);
	//print("&rarr; <a href=\"upload.php\">Upload</a> something else<br />\n");
	print("<div class='overlay'>&rarr; <a href='javascript:self.close();'>Close</a> this window</div>\n");
	print("<script>if(window.opener){
		var pos = 'x' + window.opener.location; 
		if ((pos.indexOf('insert_popup'))<1) { window.opener.location.reload();} }</script>");
	echo "</div>";
}

function print_style () {
?>
<style>
	DIV, FORM, TABLE, BODY {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 13px;
		margin:0;
		padding:0;
	}
	TABLE {
		background-color: #6D9A9A;
	}
	TD {
		text-align: right;
	}
	.light {
		background-color: #ECF2F2;
		text-align: center;
	}
	INPUT {
		border: 1px solid #527474;
		font-family: Arial, Helvetica, sans-serif;
		font-size: 11px;
		background-color: #DAE6E6;
		text-align: right;
	}
	H1, H2 {
		font-family: Georgia, Tahoma, Verdana, Arial, Helvetica, sans-serif;
		font-size: 14px;
		font-weight: bold;
		display: block;
		padding: 0 0 5px 0;
		color: #2D5A5A;
		border-bottom: 1px solid #2D5A5A;
	}
	H1 {
		margin-bottom: 15px;
	}
	H2 {
		margin: 10px 0 15px 0;
	}
	A {
		color: #880000;
		font-weight: bold;
		text-decoration: none;
	}
	A:HOVER {
		text-decoration: underline;
	}
	.button {
		border-top: 1px solid #B6CCCC;
		border-right: 1px solid #527474;
		border-bottom: 1px solid #527474;
		border-left: 1px solid #B6CCCC;
		font-family: Georgia, Tahoma, Verdana, Arial, Helvetica, sans-serif;
		font-weight: bold;
		color: white;
		background-color: #6D9A9A;
		width: 141px;
	}
	.overlay, #target {
		float: left;
		padding: 5px 0 0 0;
		clear: left;
	}
	#img_crop {  
		cursor: move;  
	}  
	#slider_crop {  
		cursor: nw-resize; 
	}
	#editor {
		margin: 10px;
	}
	.clear {
		clear: both;
		height: 0;
	}
</style>
<?php
}
?>