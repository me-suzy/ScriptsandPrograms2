/*  ##############################################
   ### MySource ------------------------------###
  ##- Frontend Common File --- Javascript ----##
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
## File: web/edit/colour_picker.js
## Desc: Javascript functions needed for the Colour Picker.
## $Source: /home/cvsroot/mysource/web/edit/colour_picker.js,v $
## $Revision: 2.4 $
## $Author: greg $
## $Date: 2002/07/11 00:12:10 $
#######################################################################
*/

var colour_fields = new Array(); //
var colour_pickers = new Array();
var colour_picker_count = 0;
function load_colour_picker(field,picker_path) {
	colour_picker_count++;
	colour_fields[colour_picker_count] = field;
	//if (colour_picker != 0 && !colour_picker.closed) colour_picker.close();
	colour_pickers[colour_picker_count] = window.open(picker_path + '/colour_picker.php?colour=' + field.value + '&pickerid='+colour_picker_count, colour_picker_count, 'toolbar=no,width=600,height=200,titlebar=false,status=no,scrollbars=no,resizeable=yes');
}

function update_colour(colour,id) {
	if (colour_fields[id].value != colour) {
		colour_fields[id].value = colour;
		show_colour_change(colour_fields[id].name);
	} else {
		colour_fields[id].value = colour;
	}
}

function show_colour_change(name) {
	if (document.getElementById) {
		var changed_image = document.getElementById('colour_change_' + name);
		if (changed_image) { changed_image.src = colour_change_image_dir + 'tick.gif'; }
		var changed_span = document.getElementById('colour_span_' + name);
		if (changed_span) {
			colour_box = document.getElementById('colour_box_' + name);
			changed_span.style.backgroundColor = colour_box.value;
		}
	} else {
		var changed_image = document['colour_change_' + name];
		if (changed_image) { changed_image.src = colour_change_image_dir + 'tick.gif'; }
	}
}

var nonhexdigits  = new RegExp('[^0-9a-fA-F]');
var nonhexletters = new RegExp('[g-zG-Z]');

function check_colour(value, allow_blanks) {
	//if (value.match(nonhexdigits)) return '000000';

	if (value.length == 0 && allow_blanks) return '';

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
	return value.toLowerCase();
}
