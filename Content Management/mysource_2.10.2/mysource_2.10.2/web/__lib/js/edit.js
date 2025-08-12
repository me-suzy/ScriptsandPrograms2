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
## File: web/edit/edit.js
## Desc: Common Javascript functions for backend forms.
## $Source: /home/cvsroot/mysource/web/__lib/js/edit.js,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/09/09 22:59:52 $
#######################################################################
*/
function popup_help(link) {
	help_popup = window.open(link, 'help_popup', 'toolbar=no,width=450,height=400,titlebar=false,scrollbars=yes');
	help_popup.focus();
}

var altpressed;
 
if(isIE4 || isIE5) {
	document.onkeydown = processkeydown;
}

function processkeydown() {
	var key;
	if(event.altKey) altpressed = true;
	if (!altpressed) return true;
	altpressed = false;
	key = String.fromCharCode(event.keyCode);
	key = key.toLowerCase();
	if(key == 's') {
		window.focus();
		if(window.checksubmitform){
			window.checksubmitform();
		}
	} else if (key == 'v') {
		window.focus();
		if (document.edit.preview_url) {
			preview_popup = window.open(document.edit.preview_url.value, 'preview', '');
		}
	} else if (key == 'h') {
		window.focus();
		if(window.undock_site_map){
			window.undock_site_map(document.edit.siteid.value);
		}
	}
}

 ////////////////////////////////////////////////////////
// These functions relate to printing the page hierarchy
function open_page(pageid) {
	document.edit.open_pageid.value = pageid;
	document.edit.active_pageid.value = pageid;
	document.edit.submit();
}

function close_page(pageid) {
	document.edit.close_pageid.value = pageid;
	document.edit.active_pageid.value = pageid;
	document.edit.submit();
}

function expand_all_page(pageid) {
	document.edit.expand_all_pageid.value = pageid;
	document.edit.active_pageid.value = pageid;
	document.edit.submit();
}

function collapse_all_page(pageid) {
	document.edit.collapse_all_pageid.value = pageid;
	document.edit.active_pageid.value = pageid;
	document.edit.submit();
}

function undock_site_map(siteid) {
	site_map_popup = window.open('', 'mysource_site_map', 'toolbar=no,status=yes,width=400,height=550,titlebar=false,scrollbars=yes,resizable=yes');
	if(site_map_popup.document.testform == null || site_map_popup.document.edit.siteid.value != siteid) {
		site_map_popup.location = 'site.php?floating_site_map=1&siteid='+siteid;
	}
	site_map_popup.focus();
}

function refresh_site_map(siteid) {
	site_map_popup = window.open('', 'mysource_site_map', 'toolbar=no,status=yes,width=400,height=550,titlebar=false,scrollbars=yes,resizable=yes');
	site_map_popup.location = 'site.php?floating_site_map=1&siteid='+siteid;
}

function dock_site_map(siteid) {
	site_map_popup = window.open('', 'mysource_site_map', 'toolbar=no,status=yes,width=350,height=550,titlebar=false,scrollbars=yes,resizable=yes');
	site_map_popup.close();
	document.edit.action.value = 'dock_floating_site_map';
	document.edit.submit();
}


function set_connect_child(id,name) {
	connect_child = id;
	connect_child_name = name;
}

var connect_child = false;


function popup_page_orderer(siteid,pageid) {
	page_orderer_popup = window.open('site.php?order_pages=1&siteid='+siteid+'&pageid='+pageid, 'page_orderer', 'toolbar=no,width=500,height=450,titlebar=false,scrollbars=yes,resizable=yes');
	page_orderer_popup.focus();
}

function popup_file_orderer(pageid) {
	file_orderer_popup = window.open('page.php?order_files=1&pageid='+pageid, 'file_orderer', 'toolbar=no,width=500,height=450,titlebar=false,scrollbars=yes,resizable=yes');
	file_orderer_popup.focus();
}

function popup_feature_help(siteid,fieldname) {
	feature_help_popup = window.open('site.php?feature_help=1&fieldname='+fieldname+'&siteid='+siteid, 'feature_helper', 'toolbar=no,width=400,height=300,titlebar=false,scrollbars=yes,resizable=yes');
	feature_help_popup.focus();
}

function refresh() {
	document.edit.submit();
}
