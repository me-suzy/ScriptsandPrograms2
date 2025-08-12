/*  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- Javascript Include Files - Javascript --##
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
## Desc: Some General JS functions 
## $Source: /home/cvsroot/squizlib/js/general.js,v $
## $Revision: 1.4 $
## $Author: dofford $
## $Date: 2003/11/07 01:25:58 $
#######################################################################
*/

 ///////////////////////////////////////////////////////
// converts certain chars to their html entity value
// converts :  '&' -> '&amp;'
//             '"' -> '&quot;'
//             '<' -> '&lt;'
//             '>' -> '&gt;'
function htmlspecialchars(str) {

	s = new String(str);

	s = s.replace(/\&/g, '&amp;');
	s = s.replace(/\"/g, '&quot;');
	s = s.replace(/</g,  '&lt;');
	s = s.replace(/>/g,  '&gt;');

	return s;

}// htmlspecialchars()

 ///////////////////////////////////////////////////////
// reverses htmlspecialchars() above
function rev_htmlspecialchars(str) {
	s = new String(str);

	s = s.replace(/\&amp;/g,  '&');
	s = s.replace(/\&quot;/g, '"');
	s = s.replace(/\&lt;/g,   '<');
	s = s.replace(/&gt;/g,    '>');

	return s;

}// rev_htmlspecialchars()

 ///////////////////////////////////////////////////////
// sorts the array then removes any duplicates 
// from it
function array_unique(arr) {

	var new_arr = new Array();
	arr.sort();
	var tmp = '';

	for(var i = 0; i < arr.length; i++) {
		if (arr[i] != tmp) {
			new_arr.push(arr[i]);
			tmp = arr[i];
		}// end if
	}// end for

	return new_arr;

}// end array_unique()

 ///////////////////////////////////////////////////////
// takes an array and a value and removes the first 
// element in the array with that value
function array_remove_element(arr, val) {

	var i = array_search(arr, val);
	if (i != null) {
		arr.splice(i, 1);
	}// end if

}// end array_remove_element()

 ///////////////////////////////////////////////////////
// takes an array and a value returns the first index
// in the array with the passed value
function array_search(arr, val) {

	for (var i in arr) {
		if (arr[i] == val) return i;
	}
	return null;

}// end array_remove_element()

 ///////////////////////////////////////////////////////
// takes an array and returns a copy of it
function array_copy(arr) {

	var new_arr = new Array();
	for (var i = 0; i < arr.length; i++) {
		new_arr[i] = arr[i];
	}
	return new_arr;

}// end array_copy()

 ///////////////////////////////////////////////////////
// takes two array and returns if they have any common elements
function array_any_common_elements(arr1, arr2) {
	for (var i in arr1) {
		//need to skip array elements inArray and validateItem
		if (arr1[i] == null) continue;

		var result = array_search(arr2, arr1[i]);
		if (result) {
			return true;
		}
	}
	return false;
}
