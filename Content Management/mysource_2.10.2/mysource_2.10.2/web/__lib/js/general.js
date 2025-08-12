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
## $Source: /home/cvsroot/mysource/web/__lib/js/general.js,v $
## $Revision: 2.5 $
## $Author: bvial $
## $Date: 2004/02/11 23:38:36 $
#######################################################################
*/

// Detect Browser
var isNS4 = (navigator.appName.indexOf("Netscape") >= 0	&& parseFloat(navigator.appVersion) >= 4) ? 1 : 0;
var isIE4 = (document.all) ? 1 : 0;
var isIE5 = (isIE4 && navigator.appVersion.indexOf("5.") >= 0) ? 1 : 0;

if(!image_popup_window) {
	var image_popup_window;
}

// Simple popup box for images. Only one at a time.
function image_popup(name, url, base_dir, width, height) {
	if (image_popup_window && !image_popup_window.closed) {
		image_popup_window.close();
	}
	image_popup_window = window.open(base_dir + '/?mysource_action=display_image&image='+escape(url)+'&title='+escape(name), 1, 'toolbar=no,width='+width+',height='+height+',nominimize,nomaximize,norestore,scrollbars=no');
	image_popup_window.focus();
}

if(!window_popup_window) {
	var window_popup_window;
}

// Simple popup window
function window_popup(url,window_features) {
	if (window_popup_window && !window_popup_window.closed) {
		window_popup_window.close();
	}
	window_popup_window = window.open(url,1,window_features);
	window_popup_window.focus();
}



//////////////////////////////////////////////////////////////////////////////////////////
// Returns the value for any type of form element
// By Blair Robertson -> 2001
//
// if select box or group of radio buttons returns the selected/checked value(s) 
//    -> for multi-select boxes returns an array of selected values
// if array of any other type of elements returns the value of the first element in array
////////////////////////////////////////////////////////////////////////////////////////////
function elementValue(element)
{
	// if element doesn't exist, die
	if (element == null) {
		return;
	}

	// if its null then probably because it's an array, take the type from the first element
	if (element.type == null) {
		element.type = element[0].type;
	}

	switch (element.type) {
		case "select-one" :
			if (element.selectedIndex >= 0) {
				return (element.options[element.selectedIndex].value)
			}
			break;
		
		case "select-multiple" :

			if (element.selectedIndex >= 0) {

				var retArr = new Array();

				for(var i = 0; i < element.options.length; i++) {
					if (element.options[i].selected) {
						retArr.push(element.options[i].value);
					}// endif
				}// end for

				if (retArr.length > 0) {
					return (retArr);
				}
			}
			break;

		case "radio" :

			// if its an array of radio buttons then cycle through them
			if (element.length != null)	{
				for(var i = 0; i < element.length; i++)	{
					if (element[i].checked)	{
						return(element[i].value);
					}// endif
				}// end for
			}
			else {
				return (element.checked) ? element.value : '';
			}
			break;

		default :
			// if its an array of elements return the first ones value
			if (element.length != null) {
				return(element[0].value);
			}
			else { // just return the value
				return(element.value);
			}

	}// end switch

	// else something not right so return blank
	return "";

}// end elementValue()


/////////////////////////////////////////////////////////////////
// IMAGE ROLLOVER FUNCTIONS
// holds all the imgs srcs for the images not currently visible
var rollover_images = new Object(); 

function AddRollover(id, rollover_src) {

	if (document.images) {
		// only if this one hasn't been declared already
		if (rollover_images[id] == null) {
			rollover_images[id] = new Image();
			rollover_images[id].src = rollover_src;
		}
	}//endif

}// end AddRollover()

function imgRoll(id) {
	var img = null;
	if (document.getElementById) {
		img = document.getElementById(id);
	}
	if (img == null && document.images) {
		img = document[id];
	}
	if (img == null) {
		 return;
	}

	var temp_src = rollover_images[id].src;
	rollover_images[id].src = img.src;
	img.src = temp_src;
}






 /////////////////////////////////////////////////////////////////////////////
// format a number into a string to the specified number of decimal places
// and put in the thousands separator, just like the PHP number_format() fn
function number_format(num, places) {
	// just to make sure we have a number
	num = parseFloat(num);
	if (isNaN(num)) num = 0;
	places = parseFloat(places);
	if (isNaN(places) || places < 0) places = 0;


	if (places == 0) {
		return _number_format_thousand_separators(Math.round(num));		
	} else {
		// if we are a zero then
		if (num == 0) {
			var str = '0.';
			for(var i = 0; i < places; i++) {
				str += '0';
			}// end for
			return str;
		} else {
			var big_num = Math.round(num * Math.pow(10, places));
			str = big_num.toString();
			var dec_place = (str.length - places);
			var dec_str    = _number_format_thousand_separators(str.substr(0, dec_place));
			var places_str = str.substr(dec_place);
			return dec_str + '.' + places_str;

		}// end if
	}// end if

}// end number_format()

function _number_format_thousand_separators(str) {

	str = str.toString();

	if (str.length <= 3) return str;

	var new_str = '';
	var i = str.length % 3;
	var prefix_comma = false;
	if (i > 0) {
		new_str += str.substr(0, i);
		prefix_comma = true;
	}
	while (i < str.length) {
		if (prefix_comma) new_str += ',';
		new_str += str.substr(i, 3);
		i += 3;
		prefix_comma = true;
	}// end while

	return new_str;

}// end _number_format_thousand_separators()
