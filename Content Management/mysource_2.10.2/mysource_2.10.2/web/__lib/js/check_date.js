/*  ##############################################
   ### MySource ------------------------------###
  ##- Backend Edit file --- Javascript -------##
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
## Desc: Javascript functions the data_box function in include/html_form.inc
## $Source: /home/cvsroot/mysource/web/__lib/js/check_date.js,v $
## $Revision: 2.1 $
## $Author: blair $
## $Date: 2002/02/25 00:39:17 $
#######################################################################
*/


// Given a select box reference, returns the current value
function getSelectValue(selectBox) {
	return eval ('document.' + selectBox + '.options[document.' + selectBox + '.selectedIndex].value');
}

// Given a select box reference, returns the current text
function getSelectText(selectBox) {
	return eval ('document.' + selectBox + '.options[document.' + selectBox + '.selectedIndex].text');
}

function check_date(form, date_name, show_time, called_onchange) {
	day     = getSelectValue(form + '.elements["day_'   + date_name + '"]');
	month   = getSelectValue(form + '.elements["month_' + date_name + '"]');
	year    = getSelectValue(form + '.elements["year_'  + date_name + '"]');
	if(show_time) {
		hour	= getSelectValue(form + '.elements["hour_' + date_name + '"]');
		min		= getSelectValue(form + '.elements["min_' + date_name + '"]');
	}
	if (month == 2) {
		if (day == 29) {
			// if not leap year
			if (((year % 4) != 0) || ( ((year % 100) == 0) && ((year % 400) != 0))) {
				alert (year + " is not a leap year, there is no " + day + "th of Feburary (for "+date_name+").");
				eval('document.' + form + '.elements["day_' + date_name + '"].selectedIndex = 0;');
				eval('document.' + form + '.elements["day_' + date_name + '"].focus()');
				return 0;
			}
		}
		else if (day > 29) {
			alert ("There is no " + day + "th of Feburary (for "+date_name+").");
			eval('document.' + form + '.elements["day_' + date_name + '"].selectedIndex = 0;');
			eval('document.' + form + '.elements["day_' + date_name + '"].focus()');
			return 0;
		}
	}
	if ((month == 4 || month == 6 || month == 9 || month == 11) && day > 30) {
		alert ("There is no " + day + "st of " + getSelectText(form + '.elements["month_' + date_name + '"]') + " (for" +date_name+").");
			eval('document.' + form + '.elements["day_' + date_name + '"].selectedIndex = 0;');
		eval('document.' + form + '.elements["day_' + date_name + '"].focus()');
		return 0;
	}

	// if the function wasn't called by the onchange event of one of the combos
	if (!called_onchange) {
		if(day<=0 || month<=0 || year<=0) {

			if(!(day==0 && month==0 && year==0)) {
				alert("This is not a valid date. Please select all dashes(-) if you wish to set a blank date.");
				if(day!=0) {
					eval('document.' + form + '.elements["day_' + date_name + '"].focus()');
				}
				else if(month!=0) {
					eval('document.' + form + '.elements["month_' + date_name + '"].focus()');
				}
				else {
					eval('document.' + form + '.elements["year_' + date_name + '"].focus()');
				}
				return 0;
			}
		}
	}
	if(show_time) {
		date_string = year + "-" + month + "-" + day + " " + hour + ":" + min;
	} else {
		date_string = year + "-" + month + "-" + day;
	}
	eval('document.' + form + '.elements["' + date_name + '"].value = date_string');
	return 1;
}

function set_date(form, date_name, set_time, date_str) {

	setSelectValue(form + '.elements["year_'  + date_name + '"]', date_str.substr(0, 4));
	setSelectValue(form + '.elements["month_' + date_name + '"]', date_str.substr(5, 2));
	setSelectValue(form + '.elements["day_'   + date_name + '"]', date_str.substr(8, 2));
	if (set_time) {
		setSelectValue(form + '.elements["hour_' + date_name + '"]', date_str.substr(11, 2));
		setSelectValue(form + '.elements["min_'  + date_name + '"]', date_str.substr(14, 2));
	}
}

// Sets the selected var for the option with the passed value
function setSelectValue(selectBox, val) {

	eval('var element = document.' + selectBox);

	// just to make sure
	if (element.type != "select-one" && element.type != "select-multiple") return;

	for(var i = 0; i < element.options.length; i++) {
		if (element.options[i].value == val) {
			element.options[i].selected = true;
			element.selectedIndex = i;
			break;
		}
	}// end for

}

