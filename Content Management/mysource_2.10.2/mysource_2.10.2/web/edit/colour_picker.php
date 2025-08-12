<?  ##############################################
   ### MySource ------------------------------###
  ##- Backend Edit file -- PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: web/edit/colour_picker.php
## Desc: A little window for choosing a colour.. talks the the window behind it.
## $Source: /home/cvsroot/mysource/web/edit/colour_picker.php,v $
## $Revision: 2.2 $
## $Author: gsherwood $
## $Date: 2003/02/27 03:34:23 $
#######################################################################
# Initialise
include_once("../init.php");
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/colour/colour.inc");
include_once("$SQUIZLIB_PATH/html_form/html_form.inc");
#---------------------------------------------------------------------#

?>
<html>
<head>
<title>Colour Picker</title>
<!-- Colour Picker -->

</head>
<?if($_GET['frame'] == 'main'){?>
<body bgcolor=#<?=$_GET['colour']?> marginwidth=0 marginheight=0 topmargin=0 leftmargin=0 onload="setup();">
<script language=javascript>
	// <!--

	var nonhexdigits  = new RegExp('[^0-9a-fA-F]');
	var nonhexletters = new RegExp('[g-zG-Z]');

	function setup() {
		update_colour('<?=$_GET['colour']?>');
	}

	function check_colour(value) {
		//if (value.match(nonhexdigits)) return '000000';
		value = value.toLowerCase();
		var c;
		for (i=0;i<value.length;i++) {
			c = value.substring(i,i+1);
			if (c.match(nonhexdigits)) {
				if (c.match(nonhexletters)) {
					value = value.substring(0,i) + 'f' + value.substring(i+1,value.length);
				} else {
					value = value.substring(0,i-1) + '0' + value.substring(i+1,value.length);
				}
			}
		}
		var extra = 6 - value.length;
		for (i=0;i<extra;i++) value += '0';
		return value;
	}

	function update_colour(value) {
		value = check_colour(value);
		document.bgColor = '#'+value;
		document.colour_form.code.value = value; // Code
		// Markers
		move_marker('r',parseInt('0x'+value.substring(0,2)));
		move_marker('g',parseInt('0x'+value.substring(2,4)));
		move_marker('b',parseInt('0x'+value.substring(4,6)));
		parent.update_colour(value);
	}

	// Establish the images for controlling the markers
	function setup_marker(c) {
		document.write('<td colspan=2 background="images/colour_picker_'+c+'.png">');
		document.write('<img src="images/blank.gif" height=14 width=1>'); // Padder
		document.write('<img name="mk_'+c+'_n" src="images/blank.gif" height=14 width=1>'); // -1
		for(i = 0; i < 256; i++) {
			document.write('<a href="javascript:set_marker(\''+c+'\','+i+');"><img name="mk_'+c+'_'+i+'" src="images/blank.gif" border =0 height=14 width=1></a>');
		}
		document.write('<img name="mk_'+c+'_256" src="images/blank.gif" height=14 width=1>'); // One over
		document.write('<img src="images/blank.gif" height=14 width=1>'); // Padder
		document.write('</td>');
	}

	// Moves a marker to the correct spot
	var current_r = 255;
	var current_g = 255;
	var current_b = 255;
	var blank_img = new Image();
	blank_img.src = 'images/blank.gif';
	var black_img = new Image();
	black_img.src = 'images/black.gif';
	var white_img = new Image();
	white_img.src = 'images/white.gif';
	function move_marker(c,v) {
		v = Math.min(Math.max(parseInt(v),0),255);
		eval('current = current_'+c+';');
		if(current == v) return false;
		// Unmark the current position
		if(current == 0) {
			document.images['mk_'+c+'_n'].src = blank_img.src;
		} else {
			document.images['mk_'+c+'_'+(current-1)].src = blank_img.src;
		}
		document.images['mk_'+c+'_'+current].src = blank_img.src;
		document.images['mk_'+c+'_'+(current+1)].src = blank_img.src;
		// Mark the new position
		if(v == 0) {
			document.images['mk_'+c+'_n'].src = black_img.src;
		} else {
			document.images['mk_'+c+'_'+(v-1)].src = black_img.src;
		}
		document.images['mk_'+c+'_'+v].src = white_img.src;
		document.images['mk_'+c+'_'+(v+1)].src = black_img.src;
		eval('current_'+c+' = v;');
	}

	// Updates stuff
	function set_marker(c,v) {
		v = Math.min(Math.max(parseInt(v),0),255);
		move_marker(c,v);
		v = dechex8(v);
		value = document.colour_form.code.value;
		if(c=='r') update_colour(v + value.substring(2,6));
		if(c=='g') update_colour(value.substring(0,2) + v + value.substring(4,6));
		if(c=='b') update_colour(value.substring(0,4) + v);
	}

	// 8bit decimal to hex converter
	var hexcode_array = new Array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
	function dechex8(dec) {
		v = Math.min(Math.max(parseInt(dec),0),255);
		return hexcode_array[Math.floor(v/16)]+hexcode_array[v%16];
	}


	//  -->
</script>
<form name=colour_form>
<table cellspacing=0 cellpadding=0 border=0 width=100% height=200><tr><td valign=bottom>
<table cellspacing=0 cellpadding=1 border=0 bgcolor=#c0c0c0 width=100%><tr><td align=center>
<table cellspacing=0 cellpadding=0 border=0>
	<tr><script language=javascript>setup_marker('r')</script></tr>
	<tr><script language=javascript>setup_marker('g')</script></tr>
	<tr><script language=javascript>setup_marker('b')</script></tr>
	<tr>
		<td><input type=button value=Done onclick="parent.done()"  style="font-family:verdana;font-size:12px;"></td>
		<td align=right><input type=text value=# size=1 maxlength=1 onfocus="blur()"  style="font-family:courier"><input type=text name=code value="<?=$_GET['colour']?>" size=6 maxlength=6 onfocus="window.status='Please enter a hex value between 000000 and ffffff';return true;" onchange="update_colour(value)" style="font-family:courier"></td>
	</tr>
</form>
</table>
</td></tr></table>
</table>
</body>
<?}elseif($_GET['frame'] == 'palette'){?>
<body bgcolor=#c0c0c0 marginwidth=0 marginheight=0 topmargin=0 leftmargin=0>
<table cellspacing=3 cellpadding=0 border=0 width=100%>
<?
$i = 0;
foreach($COLOUR_PALETTE as $name => $code) {
	if ($i++ % 2 == 0)  echo ("</tr><tr>");
	?><td bgcolor=#<?=$code?>><a href="javascript:parent.main.update_colour('<?=$code?>')"><img src="images/blank.gif" border=0 width=15 height=15></a></td><td><p style="font-family:verdana;font-weight:bold;font-size:10px;"><?=$name?></td><?
}
?>
</table>
</body>
<?}else{?>
<script language=javascript>
	// <!--

	function update_colour(value) {
		window.opener.update_colour(value,<?=$_GET['pickerid']?>);
	}

	function done() {
		window.close();
	}

	//  -->
</script>
<frameset cols="268,*" frameborder=0 border=0>
	<frame src="<?=$_SERVER['PHP_SELF']."?colour=".$_GET['colour']."&frame=main"?>" name=main noresize scrolling=no frameborder=0 border=0>
	<frame src="<?=$_SERVER['PHP_SELF']."?colour=".$_GET['colour']."&frame=palette"?>" name=palette noresize frameborder=0 border=0>
</frameset>
<?}?>
</html>