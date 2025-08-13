<?php
/////////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@users.sourceforge.net>  //
//        available at http://getid3.sourceforge.net          ///
/////////////////////////////////////////////////////////////////
//                                                             //
// getid3.jpg.php - part of getID3()                           //
// See getid3.readme.txt for more details                      //
//                                                             //
/////////////////////////////////////////////////////////////////

function getJPGHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$MP3fileInfo['fileformat'] = 'jpg';

	rewind($fd);
	include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
	list($width, $height, $type) = GetDataImageSize(fread($fd, $MP3fileInfo['filesize']));
	if ($type == 2) {

		$MP3fileInfo['resolution_x'] = $width;
		$MP3fileInfo['resolution_y'] = $height;

	} else {
		unset($MP3fileInfo['fileformat']);
	}

	return TRUE;
}

?>