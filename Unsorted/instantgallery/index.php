<?
/*
	phpInstantGallery v.1.1 by Isaac McGowan <isaac@ikemcg.com>
	
	These scripts are released under the terms of the GENERAL PUBLIC LICENSE (GPL).
	Do what you will with them.  Change them, share them, and redistribute them.
	Just don't call them your own.  See the enclosed file GPL to read the terms of the
	GENERAL PUBLIC LICENSE.  Or see http://www.gnu.org/copyleft/gpl.html
 */

require("config.php");
require("functions.php");

// $docroot and $galleryroot defined in config.php
$gallerydir = $docroot . $galleryroot;

if (!empty($HTTP_GET_VARS['gallery'])) {
	
	// get path to requested gallery.
	$gallery = $HTTP_GET_VARS['gallery'];
	$gallerydir = $gallerydir . "/" . $gallery;
	
	// get image list.  get image list array posted to this page from user coming from
	// a page within the same gallery.  otherwise, scan directory for image list
	$imglist = $HTTP_POST_VARS['imglist'] ? $HTTP_POST_VARS['imglist'] : getImgList($gallerydir);

	// get image count
	$imgcnt = count($imglist);

	// use default template if template not specified in query string
	$tmplt = $HTTP_GET_VARS['tmplt'] ? $HTTP_GET_VARS['tmplt'] : $defaulttemplate;

	// determine whether thumbnail or image page
	// if image page, get path to image that is to be displayed
	if (empty($HTTP_GET_VARS['imgno'])) {
		$pgtype = 'thumb';
	} else {
		$imgno = $HTTP_GET_VARS['imgno'];
		$img = $galleryroot . "/" . $gallery . "/" . $imglist[$imgno - 1];
		$pgtype = 'image';
	}

	// include output templates
	require('./templates/' . $tmplt . '/header.php');
	require('./templates/' . $tmplt . '/' . $pgtype . '_page.php');

} else {
	header("Location: http://" . $HTTP_SERVER_VARS['HTTP_HOST'] . dirname($HTTP_SERVER_VARS['PHP_SELF']) . "/admin.php");
}
?>


